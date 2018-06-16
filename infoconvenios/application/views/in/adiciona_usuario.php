<script language="JavaScript">
    function enviardados() {
        var theOddOnes = document.getElementsByClassName("obrigatorio");
        for (var i = 0; i < theOddOnes.length; i++) {

            if (trim(theOddOnes[i].value) == '') {
                alert("Preencha os campos obrigatórios! (com asterisco)");
                theOddOnes[i].focus();
                return false;
            }
        }

        //se os dados do usuarios nao forem válidos, emite uma mensagem de erro
        if (!verificaValidade(document.cadastrar.login.value)) {
            alert("Só são permitidos números e letras (todas menos a 'ç') no campo de apelido!");
            document.cadastrar.login.focus();
            return false;
        }
        if (!valida_cpf(document.cadastrar.cpf.value)) {
            alert("CPF inválido");
            document.cadastrar.cpf.focus();
            return false;
        }
        return true;
    }

    function trim(str) {
        return str.replace(/^\s+|\s+$/g, "");
    }

    function valida_cpf(cpf) {
        var numeros, digitos, soma, i, resultado, digitos_iguais;
        digitos_iguais = 1;
        if (cpf.length < 11)
            return false;
        for (i = 0; i < cpf.length - 1; i++)
            if (cpf.charAt(i) != cpf.charAt(i + 1)) {
                digitos_iguais = 0;
                break;
            }
        if (!digitos_iguais) {
            numeros = cpf.substring(0, 9);
            digitos = cpf.substring(9);
            soma = 0;
            for (i = 10; i > 1; i--)
                soma += numeros.charAt(10 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;
            numeros = cpf.substring(0, 10);
            soma = 0;
            for (i = 11; i > 1; i--)
                soma += numeros.charAt(11 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;
            return true;
        } else
            return false;
    }
    //verifica se os dados foram enviados com caracteres válidos
    function verificaValidade(nome) {
        var valores = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 _-/*+,.";

        for (i = 0; i < nome.length; i++) {
            if (valores.indexOf(nome.charAt(i), 0) == -1)
                return false;
        }
        return true
    }

    function Mascara(o, f) {
        v_obj = o
        v_fun = f
        setTimeout("execmascara()", 1)
    }

    /*Função que Executa os objetos*/
    function execmascara() {
        v_obj.value = v_fun(v_obj.value)
    }

    function Telefone(v) {
        v = v.replace(/\D/g, "")
        v = v.replace(/^(\d\d)(\d)/g, "($1) $2")
        v = v.replace(/(\d{4})(\d)/, "$1-$2")
        return v
    }
</script>

<style>
    body {
        padding-top: 5% !important;
    }
</style>
<h2 class="innerAll margin-none text-center"><i class="fa fa-lock"></i> Adicionar Usuário</h2>

<div class="login spacing-x2">

    <div class="col-md-6 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-body innerAll">

                <form name="cadastrar" class="form-horizontal" method="post" action="<?php echo  base_url();?>index.php/inicio/cadastrar" enctype="multipart/form-data" onSubmit="return enviardados();">


                    <input type="hidden" name="tipoPessoa" value="<?php echo $tipo_usuario;?>">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nome*</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="nome" value="" id="nome" class="obrigatorio" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">CPF*</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="cpf" value="" id="cpf" class="obrigatorio" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Login*</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="login" value="" id="login" class="obrigatorio" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Senha*</label>
                        <div class="col-sm-9">

                            <input type="password" class="form-control" name="senha" value="" id="senha" class="obrigatorio" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Email*</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="email" value="" id="email" class="obrigatorio" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Telefone*</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="telefone" value="" maxlength=15 id="telefone" onKeyDown="Mascara(this,Telefone);" onKeyPress="Mascara(this,Telefone);" onKeyUp="Mascara(this,Telefone);" class="obrigatorio" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Escolaridade</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="escolaridade" value="" id="escolaridade" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nome da Instituição</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="nomeInstituicao" value="" id="nomeInstituicao" />

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Endereço</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="endereco" value="" id="endereco" />

                        </div>
                    </div>

                    <div class="center">
                        <input type="submit" class="btn btn-primary" id="form_submit" onclick="" value="Cadastrar" name="cadastra" />
                    </div>
                </form>
                <div>

                </div>
            </div>
        </div>
	</div>
</div>

