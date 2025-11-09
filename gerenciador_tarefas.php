<?php
session_start();

$arquivo = "tarefas.json";
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT));
}

$tarefas = json_decode(file_get_contents($arquivo), true);

if (!is_array($tarefas)) {
    $tarefas = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["adicionar"]) && !empty($_POST["nome_tarefa"])) {
        $tarefas[] = [
            "text" => $_POST["nome_tarefa"],
            "concluida" => false
        ];
        $_SESSION['msg'] = "Tarefa adicionada com sucesso";
    }

    if (isset($_POST["excluir"]) && isset($_POST["id"])) {
        $id = (int) $_POST["id"];
        unset($tarefas[$id]);
        $tarefas = array_values($tarefas);
    }

    if (isset($_POST["mudar"], $_POST["id"])) {
        $id = (int) $_POST["id"];
        $tarefas[$id]["concluida"] = !$tarefas[$id]["concluida"];
    }

    file_put_contents($arquivo, json_encode($tarefas, JSON_PRETTY_PRINT));

    header("Location: gerenciador_tarefas.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskyFluffy</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
    :root{
        --color-primary: #20406D;
        --color-secondary: #E95C5C;
        --color-terciary: #4E8DE6;
        --color-icon: #80A86A;
        --animate-sucess: sucess 5s ease-in-out;
      }

        @layer base {
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: "Poppins", sans-serif; 
            }
        }

        body{
            background-color: #E6F1FF;
        }

    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        width: 90%;
    }

    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 30px;
        width: 30px;
        background-color: #8DB4EB;
        border-radius: 50px;
    }

    .rounded{
	    border-radius: 20px;
    }

    .container:hover input ~ .checkmark {
        background-color: #C8DFFF;
    }

    .container input:checked ~ .checkmark {
        background-color: #5355D4;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .container input:checked ~ .checkmark:after {
        display: block;
    }

    .container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 10px;
        height: 16px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    @keyframes sucess{
        0% {
            opacity: 0;
            bottom: 0px;
        }

        5%{
            opacity: 1;
            bottom: 20px;
        }

        95%{
            opacity: 1;
            bottom: 20px;
        }

        100%{
            opacity: 0;
            bottom: 0px;
        }
    }

    


    </style>

</head>

<body>

    <?php 
    if (!empty($_SESSION['msg'])){
    ?>
    <div class="absolute z-100 bottom-0 flex justify-center items-center w-[100%] h-[50vh] overflow-hidden animate-(--animate-sucess) " id="msgSuccess">
        <div class="absolute bottom-10 w-100 h-15 bg-white flex justify-center items-center shadow-lg rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="48px" fill="#319631"><path d="M480-80q-85 0-158-30.5T195-195q-54-54-84.5-127T80-480q0-84 30.5-157T195-764q54-54 127-85t158-31q75 0 140 24t117 66l-43 43q-44-35-98-54t-116-19q-145 0-242.5 97.5T140-480q0 145 97.5 242.5T480-140q145 0 242.5-97.5T820-480q0-30-4.5-58.5T802-594l46-46q16 37 24 77t8 83q0 85-31 158t-85 127q-54 54-127 84.5T480-80Zm-59-218L256-464l45-45 120 120 414-414 46 45-460 460Z"/></svg>
            <?= $_SESSION['msg']; ?>
        </div>
    </div>
        <?php unset($_SESSION['msg']); ?>
    <?php 
        }
    ?>

    <div class="flex">

    <header class="w-120 h-[100vh] bg-(--color-primary) z-2 sticky top-0">

        <div class="p-3 hidden cursor-pointer hover:opacity-60" id="hamburguer">
            <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#FFFFFF"><path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/></svg>
        </div>

        <div class="p-3 hover:opacity-60 cursor-pointer" id="close">
            <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#FFFFFF"><path d="m249-207-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg>
        </div>


        <nav>

            <div>

            </div>

            <div class="flex flex-col">
                <nav>
                    <ul class="mt-10 pl-3 text-white flex flex-col gap-12 text-[1.25rem]">

                            <li class="flex items-center">
                         
                                <a href="#" id="link_tarefas" class="flex items-center"><svg class="hover:opacity-60" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="48px" fill="#FFFFFF"><path d="M480-80q-85 0-158-30.5T195-195q-54-54-84.5-127T80-480q0-84 30.5-157T195-764q54-54 127-85t158-31q75 0 140 24t117 66l-43 43q-44-35-98-54t-116-19q-145 0-242.5 97.5T140-480q0 145 97.5 242.5T480-140q145 0 242.5-97.5T820-480q0-30-4.5-58.5T802-594l46-46q16 37 24 77t8 83q0 85-31 158t-85 127q-54 54-127 84.5T480-80Zm-59-218L256-464l45-45 120 120 414-414 46 45-460 460Z"/></svg>                            
                                <span class="ml-3 hover:border-b-[2px] hover:mb-[-2px]" id="nav-list1">Tarefas</span></a>
                            </button>
                            </li>
                            

                        <li class="flex items-center">
                         
                                <a href="#" id="link_tarefas-concluidas" class="flex items-center">
                                <svg class="hover:opacity-60" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="48px" fill="#FFFFFF"><path d="M222-214 80-356l42-42 100 99 179-179 42 43-221 221Zm0-320L80-676l42-42 100 99 179-179 42 43-221 221Zm298 244v-60h360v60H520Zm0-320v-60h360v60H520Z"/></svg>
                                <span class="ml-3 hover:border-b-[2px] hover:mb-[-2px]" id="nav-list2">Tarefas concluídas</span>
                                </a>
                         
                        </li>

                        <li class="flex items-center">
                                <a href="#" id="link_adcionar-tarefa" class="flex items-center">
                                <svg class="hover:opacity-60" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="48px" fill="#FFFFFF"><path d="M450-450H200v-60h250v-250h60v250h250v60H510v250h-60v-250Z"/></svg>
                                <span class="ml-3 hover:border-b-[2px] hover:mb-[-2px]" id="nav-list3">Adicionar tarefa</span>
                                </a>
                            
                        </li>

                        </ul>

                </nav>
            </div>

        </nav>
    </header>

    <main class="w-full pl-5">

        <div id="content-adctarefas" class="">
            <h1 class="m-10 text-[3rem] font-medium text-(--color-secondary)">TaskyFluffy</h1>

            <h2 class="ml-10 text-[2.2rem] font-extralight mb-7">Adicionar nova tarefa</h2>

            <div class="ml-10">
                <form method="post" id="adicionarForm">
                    <div class="relative flex items-center mb-2">
                        <input id="newtask" name="nome_tarefa" type="text" class="w-[60%] h-15 bg-white p-5 pl-20 rounded-xl text-[1.1rem] shadow-md focus:outline-none" placeholder="Ir no mercado">
                        <a href="index.php?del_task"></a>
                        <svg class="absolute m-2" xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#666666"><path d="M450-450H200v-60h250v-250h60v250h250v60H510v250h-60v-250Z"/></svg>
                    </div>
                        
                    <div class="text-red-700 hidden" id="errorMsg">
                        <p>Adicione um nome de tarefa válido</p>
                    </div>
                    
                
                    <button id="btnSave" name="adicionar" class="mt-7 cursor-pointer hover:opacity-60 text-white bg-(--color-terciary) p-2 flex justify-center items-center w-30 h-10 text-[1.2rem] font-medium rounded-xl">Salvar</button>
                </form>
            </div>
        </div>

        <div id="content-tarefas" class="hidden">
            <h1 class="m-10 text-[3rem] font-medium text-(--color-secondary)">TaskyFluffy</h1>
            <h2 class="ml-10 text-[2.2rem] font-light mb-7">Tarefas</h2>

            <div class="flex flex-col">
                <ul>
                <?php $Pendentes = false; ?> 
                      
                    <?php foreach ($tarefas as $i => $tarefa){ 
                    if (!$tarefa["concluida"]){ 
                        $Pendentes = true;
                        ?>

                    <li data-id="<?= $i ?>" class="<?php $tarefa['concluida'] ? 'concluida' : '' ?> mb-5 ml-10 bg-white pl-3 flex items-center w-[60%] h-15 rounded-xl">
                    <div class="relative w-[100%] flex">
                        <form method="post" class="relative w-[100%] flex items-center">
                            <label class="container">    
                                    <input type="checkbox" class="rounded check-tarefa">
                                    <span class="checkmark"></span>
                                    <div class="ml-3 w-full"><?php echo htmlspecialchars($tarefa["text"]) ?></div>    
                            </label>
                        </form>
                        <form method="post" class="relative w-[10%]">
                            <button type="submit" name="excluir">
                                <input type="hidden" name="id" value="<?= $i ?>">
                                <svg class="absolute right-2 top-0 bottom-4 hover:opacity-60 hover:cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="48px" fill="#E95C5C"><path d="M261-120q-24.75 0-42.37-17.63Q201-155.25 201-180v-570h-41v-60h188v-30h264v30h188v60h-41v570q0 24-18 42t-42 18H261Zm438-630H261v570h438v-570ZM367-266h60v-399h-60v399Zm166 0h60v-399h-60v399ZM261-750v570-570Z"/></svg>
                            </button>
                        </form>
                    </div>      
                    </li>

                    <?php
                        }
                    }

                    if (!$Pendentes){ ?>
                        <div class="mt-10 w-full flex flex-col items-center h-auto">
                            <p class="text-[2rem] text-(--color-primary) font-light">Você ainda não tem tarefas para concluir.</p>
                            <div class="mt-15 w-[450px] h-100">
                                <img class="opacity-90" src="https://i.pinimg.com/736x/70/eb/70/70eb7086d4bcb780e208454a9ac39ff9.jpg" alt="" class="w-full object-contain"></div>
                            </div>
                    <?php        
                    }
                    
                    ?>
                </ul>  
                    
                </div>
        </div>

        <div id="content-concltarefas" class="hidden">
            <h1 class="m-10 text-[3rem] font-medium text-(--color-secondary)">TaskyFluffy</h1>
            <h2 class="ml-10 text-[2.2rem] font-light mb-7">Tarefas concluídas</h2>

            <div class="flex flex-col">
              
                <ul>
                    <?php 
                    $Concluidas = false;
                    ?>
                    <?php foreach ($tarefas as $i => $tarefa){ ?>
                    <?php 
                    if ($tarefa["concluida"]){
                    $Concluidas = true; ?>

                    <li data-id="<?= $i ?>" class="concluida mb-5 ml-10 bg-white pl-3 flex items-center w-[60%] h-15 rounded-xl">
                    <div class="relative w-[100%] flex">
                            <label class="container">    
                                    <input type="checkbox" checked="checked" class="rounded check-tarefa" name="mudar">
                                    <span class="checkmark"></span>
                                    <div class="ml-3 line-through opacity-60"><?php echo htmlspecialchars($tarefa["text"]) ?></div>    
                            </label>
                            <form method="post" class="relative w-[10%]">
                                <button type="submit" name="excluir" class="excluir">
                                    <input type="hidden" name="id" value="<?= $i ?>">
                                    <svg class="absolute right-2 top-0 bottom-4 hover:opacity-60 hover:cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="48px" fill="#E95C5C"><path d="M261-120q-24.75 0-42.37-17.63Q201-155.25 201-180v-570h-41v-60h188v-30h264v30h188v60h-41v570q0 24-18 42t-42 18H261Zm438-630H261v570h438v-570ZM367-266h60v-399h-60v399Zm166 0h60v-399h-60v399ZM261-750v570-570Z"/></svg>
                                </button>
                            </form>
                    </div>      
                    </li>
                    <?php 
                        }
                    }

                    ?>

                    <?php
                        if (!$Concluidas){ ?>

                        <div class="mt-10 w-full flex flex-col items-center h-auto">
                            <p class="text-[2rem] text-(--color-primary) font-light">Você ainda não tem tarefas concluídas.</p>
                            <div class="mt-15 w-[450px] h-100">
                                <img class="opacity-90" src="https://i.pinimg.com/736x/70/eb/70/70eb7086d4bcb780e208454a9ac39ff9.jpg" alt="" class="w-full object-contain">
                            </div>
                        </div>

                    <?php 
                        }
                    ?>

                    

                    </ul>
                    
                </div>
        </div>
            

        </div>
        

    </main>

    <aside class="w-100">

    </aside>

    </div>
    
<script src="script.js"></script>
        
</body>
</html>
