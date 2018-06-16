<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Gerenciamento</title>
    </head>
    <body>
        <?php
        if ($this->session->flashdata('msg')) {
            echo $this->session->flashdata('msg');
        }
        ?>
        
        <ul>
            <li>Usuários</li>
            <li>Gestores</li>
            <li>Cnpj's</li>
        </ul>
           
    </body>
</html>

