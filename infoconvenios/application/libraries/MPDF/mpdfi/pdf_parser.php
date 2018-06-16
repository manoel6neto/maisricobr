<?php
//
//  FPDI - Version 1.2
//
//    Copyright 2004-2007 Setasign - Jan Slabon
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

if (!defined ('PDF_TYPE_NULL'))
    define ('PDF_TYPE_NULL', 0);
if (!defined ('PDF_TYPE_NUMERIC'))
    define ('PDF_TYPE_NUMERIC', 1);
if (!defined ('PDF_TYPE_TOKEN'))
    define ('PDF_TYPE_TOKEN', 2);
if (!defined ('PDF_TYPE_HEX'))
    define ('PDF_TYPE_HEX', 3);
if (!defined ('PDF_TYPE_STRING'))
    define ('PDF_TYPE_STRING', 4);
if (!defined ('PDF_TYPE_DICTIONARY'))
    define ('PDF_TYPE_DICTIONARY', 5);
if (!defined ('PDF_TYPE_ARRAY'))
    define ('PDF_TYPE_ARRAY', 6);
if (!defined ('PDF_TYPE_OBJDEC'))
    define ('PDF_TYPE_OBJDEC', 7);
if (!defined ('PDF_TYPE_OBJREF'))
    define ('PDF_TYPE_OBJREF', 8);
if (!defined ('PDF_TYPE_OBJECT'))
    define ('PDF_TYPE_OBJECT', 9);
if (!defined ('PDF_TYPE_STREAM'))
    define ('PDF_TYPE_STREAM', 10);


class pdf_parser {
	
	/**
     * Filename
     * @var string
     */
    var $filename;
    
    /**
     * File resource
     * @var resource
     */
    var $f;
    
    /**
     * PDF Context
     * @var object pdf_context-Instance
     */
    var $c;
    
    /**
     * xref-Data
     * @var array
     */
    var $xref;

    /**
     * root-Object
     * @var array
     */
    var $root;
	
    // mPDF 4.0 Added flag to show success on loading file
    var $success;
    var $errormsg;

    /**
     * Constructor
     *
     * @param string $filename  Source-Filename
     */
	function pdf_parser($filename) {
        $this->filename = $filename;
	  // mPDF 4.0
	  $this->success = true;

        $this->f = @fopen($this->filename, "rb");

        if (!$this->f) {
            $this->success = false;
            $this->errormsg = sprintf("Cannot open %s !", $filename);
		return false;
	  }
	// mPDF 5.0 Removed pass by reference =&
        $this->c = new pdf_context($this->f);
        // Read xref-Data
	  $offset = $this->pdf_find_xref();
        if ($offset===false) {
            $this->success = false;
            $this->errormsg = sprintf("Cannot open %s !", $filename);
		return false;
	  }
        $this->pdf_read_xref($this->xref, $offset);
        if ($this->success == false) { return false; }

        // Check for Encryption
        $this->getEncryption();
        if ($this->success == false) { return false; }

        // Read root
        $this->pdf_read_root();
        if ($this->success == false) { return false; }
    }
    
    /**
     * Close the opened file
     */
    function closeFile() {
    	if (isset($this->f)) {
    	    fclose($this->f);	
    		unset($this->f);
    	}	
    }
    
      /**
     * Print Error and die
     *
     * @param string $msg  Error-Message
     */
    function error($msg) {
    	die("<b>PDF-Parser Error:</b> ".$msg);	
    }
  
    /**
     * Check Trailer for Encryption
     */
    function getEncryption() {
        if (isset($this->xref['trailer'][1]['/Encrypt'])) {
	 	// mPDF 4.0
           	$this->success = false;
            $this->errormsg = sprintf("File is encrypted!");
		return false;
        }
    }
    
	/**
     * Find/Return /Root
     *
     * @return array
     */
    function pdf_find_root() {
        if ($this->xref['trailer'][1]['/Root'][0] != PDF_TYPE_OBJREF) {
	 	// mPDF 4.0
           	$this->success = false;
            $this->errormsg = sprintf("Wrong Type of Root-Element! Must be an indirect reference");
		return false;
        }
        return $this->xref['trailer'][1]['/Root'];
    }

    /**
     * Read the /Root
     */
    function pdf_read_root() {
        // read root
	  $root = $this->pdf_find_root();
        if ($root ===false) {
            $this->success = false;
		return false;
	  }
        $this->root = $this->pdf_resolve_object($this->c, $root);
    }
    
    /**
     * Find the xref-Table
     */
    function pdf_find_xref() {
       	fseek ($this->f, -min(filesize($this->filename),1500), SEEK_END);
        $data = fread($this->f, 1500);
        
        $pos = strlen($data) - strpos(strrev($data), strrev('startxref')); 
        $data = substr($data, $pos);
        
        if (!preg_match('/\s*(\d+).*$/s', $data, $matches)) {
	 	// mPDF 4.0
           	$this->success = false;
            $this->errormsg = sprintf("Unable to find pointer to xref table");
		return false;
    	}

    	return (int) $matches[1];
    }

    /**
     * Read xref-table
     *
     * @param array $result Array of xref-table
     * @param integer $offset of xref-table
     * @param integer $start start-position in xref-table
     * @param integer $end end-position in xref-table
     */
    function pdf_read_xref(&$result, $offset, $start = null, $end = null) {
        if (is_null ($start) || is_null ($end)) {
		fseek($this->f, $o_pos = $offset);
            $data = trim(fgets($this->f,1024));

            if (strlen($data) == 0) 
                $data = trim(fgets($this->f,1024));

            if ($data !== 'xref') {
            	fseek($this->f, $o_pos);
            	$data = trim(_fgets($this->f, true));
            	if ($data !== 'xref') {
            	    if (preg_match('/(.*xref)(.*)/m', $data, $m)) { // xref 0 128 - in one line
                        fseek($this->f, $o_pos+strlen($m[1]));            	        
            	    } elseif (preg_match('/(x|r|e|f)+/', $data, $m)) { // correct invalid xref-pointer
            	        $tmpOffset = $offset-4+strlen($m[0]);
            	        $this->pdf_read_xref($result, $tmpOffset, $start, $end);
            	        return;
                    } else {
	 			// mPDF 4.0
           			$this->success = false;
            		$this->errormsg = sprintf("Unable to find xref table - Maybe a Problem with 'auto_detect_line_endings'");
				return;
            	    }
            	}
    		}

    		$o_pos = ftell($this->f);
    	    $data = explode(' ', trim(fgets($this->f,1024)));
			if (count($data) != 2) {
    	        fseek($this->f, $o_pos);
    	        $data = explode(' ', trim(_fgets($this->f, true)));
			
            	if (count($data) != 2) {
            	    if (count($data) > 2) { // no lineending
            	        $n_pos = $o_pos+strlen($data[0])+strlen($data[1])+2;
            	        fseek($this->f, $n_pos);
            	    } else {
	 			// mPDF 4.0
           			$this->success = false;
            		$this->errormsg = sprintf("Unexpected header in xref table");
				return;
            	    }
            	}
            }
            $start = $data[0];
            $end = $start + $data[1];
        }

        if (!isset($result['xref_location'])) {
            $result['xref_location'] = $offset;
    	}

    	if (!isset($result['max_object']) || $end > $result['max_object']) {
    	    $result['max_object'] = $end;
    	}

    	for (; $start < $end; $start++) {
    		$data = ltrim(fread($this->f, 20)); // Spezifications says: 20 bytes including newlines
    		$offset = substr($data, 0, 10);
    		$generation = substr($data, 11, 5);

    	    if (!isset ($result['xref'][$start][(int) $generation])) {
    	    	$result['xref'][$start][(int) $generation] = (int) $offset;
    	    }
    	}

    	$o_pos = ftell($this->f);
        $data = fgets($this->f,1024);
		if (strlen(trim($data)) == 0) 
		    $data = fgets($this->f, 1024);

        if (preg_match("/trailer/",$data)) {
            if (preg_match("/(.*trailer[ \n\r]*)/",$data,$m)) {
            	fseek($this->f, $o_pos+strlen($m[1]));
    		}

			// mPDF 5.0 Removed pass by reference =&
			$c = new pdf_context($this->f);
    	    $trailer = $this->pdf_read_value($c);
    	    
    	    if (isset($trailer[1]['/Prev'])) {
    	    	$this->pdf_read_xref($result, $trailer[1]['/Prev'][1]);
    		    $result['trailer'][1] = array_merge($result['trailer'][1], $trailer[1]);
    	    } else {
    	        $result['trailer'] = $trailer;
            }
    	} else {
    	    $data = explode(' ', trim($data));
            
    		if (count($data) != 2) {
            	fseek($this->f, $o_pos);
        		$data = explode(' ', trim (_fgets ($this->f, true)));

        		if (count($data) != 2) {
	 			// mPDF 4.0
           			$this->success = false;
            		$this->errormsg = sprintf("Unexpected data in xref table");
				return;
        		}
		    }
		    
		    $this->pdf_read_xref($result, null, (int) $data[0], (int) $data[0] + (int) $data[1]);
    	}
    }


    /**
     * Reads an Value
     *
     * @param object $c pdf_context
     * @param string $token a Token
     * @return mixed
     */
    function pdf_read_value(&$c, $token = null) {
    	if (is_null($token)) {
    	    $token = $this->pdf_read_token($c);
    	}
    	
        if ($token === false) {
    	    return false;
    	}

       	switch ($token) {
            case	'<':
    			// This is a hex string.
    			// Read the value, then the terminator

                $pos = $c->offset;

    			while(1) {

           