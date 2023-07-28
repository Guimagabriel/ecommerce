<?php

function login($vars, $container)
{
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('login', [
        'error' => VirtualStore\Models\User::getError(),
        'errorRegister' => VirtualStore\Models\User::getErrorRegister(),
        'registerValues' => (isset($_SESSION['registerValues']) ? $_SESSION['registerValues'] : ['name'=>'', 'email'=>'', 'phone'=>''])
    ]);
}

function loginPost($vars, $container)
{
    try {
        VirtualStore\Models\User::login($_POST['login'], $_POST['password']);
    } catch(Exception $e) {
        VirtualStore\Models\User::setError($e->getMessage());
    }
    
    header("Location: /");
    exit;
}

function createPost($vars, $container)
{
    $_SESSION['registerValues'] = $_POST;

    if(!isset($_POST['name']) || $_POST['name'] == '') {

        VirtualStore\Models\User::setErrorRegister("Preencha o seu nome.");
        header("Location: /login");
        exit;
    }

    if(!isset($_POST['email']) || $_POST['email'] == '') {

        VirtualStore\Models\User::setErrorRegister("Preencha o email.");
        header("Location: /login");
        exit;
    }

    if(!isset($_POST['password']) || $_POST['password'] == '') {

        VirtualStore\Models\User::setErrorRegister("Preencha a senha.");
        header("Location: /login");
        exit;
    }

    if(VirtualStore\Models\User::checkLoginExist($_POST['email']) === true) {

        VirtualStore\Models\User::setErrorRegister("Este email já existe.");
        header("Location: /login");
        exit;
    }
    

    $user = $container->get(VirtualStore\Models\User::class);
    $user->setData(['inadmin' => 0,
        'deslogin' => $_POST['email'],
        'desemail' => $_POST['email'],
        'despassword' => $_POST['password'],
        'desperson' => $_POST['name'],
        'nrphone' => $_POST['phone']
    ]);

    $user->save();
    VirtualStore\Models\User::login($_POST['email'], $_POST['password']);

    header("Location: /cart");
    exit;

}

function logout($vars, $container)
{
    VirtualStore\Models\User::logout();

    header("Location: /login");
    exit;
}

function profile($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);
    $user = VirtualStore\Models\User::getFromSession();
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('profile', ['user' => $user->getValues(), 'message' => VirtualStore\Message::getMessage()]);
    
}

function profilePost($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);

    if(!isset($_POST['desperson']) || $_POST['desperson'] === "") {
        VirtualStore\Message::setMessage("Preencha o seu nome!", "error");

        header("Location: /profile");
        exit;
    }

    if(!isset($_POST['desemail']) || $_POST['desemail'] === "") {
        VirtualStore\Message::setMessage("Preencha o seu email!", "error");

        header("Location: /profile");
        exit;
    }

    $user = VirtualStore\Models\User::getFromSession();

    if($_POST['desemail'] !== $user->getdesemail()) {

        if(VirtualStore\Models\User::checkLoginExist($_POST['desemail'])) {

            VirtualStore\Message::setMessage("Este email já está cadastrado!", "error");

            header("Location: /profile");
            exit;
        }
    }    

    $_POST['inadmin'] = $user->getinadmin();
    $_POST['despassword'] = $user->getdespassword();
    $_POST['deslogin'] = $_POST['desemail'];

    $user->setData($_POST);
    $user->update();

    VirtualStore\Models\User::updateSessionData($user->getiduser());
    VirtualStore\Message::setMessage("Dados alterados com sucesso!", "success");

    header("Location: /profile");
    exit;
}

function changePassword($vars, $container)
{   
    VirtualStore\Models\User::verifyLogin(false);
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage("profile-change-password", ['error' => VirtualStore\Message::getMessage()]);
}

function changePasswordPost($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);

    if(!isset($_POST['current_pass']) || $_POST['current_pass'] === '') {
        VirtualStore\Message::setMessage("Digite a senha atual", "error");

        header("Location: /profile/change-password");
        exit;
    }

    if(!isset($_POST['new_pass']) || $_POST['new_pass'] === '') {
        VirtualStore\Message::setMessage("Digite a nova senha", "error");

        header("Location: /profile/change-password");
        exit;
    }

    if(!isset($_POST['new_pass_confirm']) || $_POST['new_pass_confirm'] === '') {
        VirtualStore\Message::setMessage("Confirme a nova senha", "error");

        header("Location: /profile/change-password");
        exit;
    }

    if($_POST['current_pass'] === $_POST['new_pass']) {
        VirtualStore\Message::setMessage("A senha deve ser diferente da atual", "error");

        header("Location: /profile/change-password");
        exit;
    }

    if($_POST['new_pass'] != $_POST['new_pass_confirm']) {
        VirtualStore\Message::setMessage("As novas senhas devem coincidir", "error");

        header("Location: /profile/change-password");
        exit;
    }



    $user = VirtualStore\Models\User::getFromSession();
    if(!password_verify($_POST['current_pass'], $user->getdespassword())) {
        VirtualStore\Message::setMessage("Senha inválida", "error");

        header("Location: /profile/change-password");
        exit;
    }

    $user->setdespassword($_POST['new_pass']);
    $user->update();

    VirtualStore\Message::setMessage("Senha alterada com sucesso", "success");

    header("Location: /profile/change-password");
    exit;
    

}
