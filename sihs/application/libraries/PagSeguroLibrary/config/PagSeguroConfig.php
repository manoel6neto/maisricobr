<?php 

/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

$PagSeguroConfig = array();

$PagSeguroConfig['environment'] = "production"; // production, sandbox

$PagSeguroConfig['credentials'] = array();
$PagSeguroConfig['credentials']['email'] = "pagnet@physisbrasil.com.br";
$PagSeguroConfig['credentials']['token']['production'] = "17C59AD908254805A88DBA532C863BCB";
$PagSeguroConfig['credentials']['token']['sandbox'] = "8A2066F5BBE44BEAB0D4434ECC6D5E29";
$PagSeguroConfig['credentials']['appId']['production'] = "your__production_pagseguro_application_id";
$PagSeguroConfig['credentials']['appId']['sandbox'] = "app3768362841";
$PagSeguroConfig['credentials']['appKey']['production'] = "your_production_application_key";
$PagSeguroConfig['credentials']['appKey']['sandbox'] = "C2BB41BB60605BE884C36F99C5FD6231";

$PagSeguroConfig['application'] = array();
$PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

$PagSeguroConfig['log'] = array();
$PagSeguroConfig['log']['active'] = false;
// Informe o path completo (relativo ao path da lib) para o arquivo, ex.: ../PagSeguroLibrary/logs.txt
$PagSeguroConfig['log']['fileLocation'] = "./log_pag.txt";
