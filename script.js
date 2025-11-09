        const link_contentTask = document.getElementById("link_tarefas");
        const link_contentconTask = document.getElementById("link_tarefas-concluidas");
        const link_contentaddTask = document.getElementById("link_adcionar-tarefa");

        link_contentTask.addEventListener("click", () => {
            document.getElementById("content-tarefas").style.display = 'block';
            document.getElementById("content-concltarefas").style.display = 'none';
            document.getElementById("content-adctarefas").style.display = 'none';
        });

        link_contentconTask.addEventListener("click", () => {
            document.getElementById("content-tarefas").style.display = 'none';
            document.getElementById("content-concltarefas").style.display = 'block';
            document.getElementById("content-adctarefas").style.display = 'none';
        });

        link_contentaddTask.addEventListener("click", () => {
            document.getElementById("content-tarefas").style.display = 'none';
            document.getElementById("content-concltarefas").style.display = 'none';
            document.getElementById("content-adctarefas").style.display = 'block';
        });

        const hamburguer = document.getElementById("hamburguer");
        const close = document.getElementById("close");

        close.addEventListener("click", () => {
            document.getElementById("nav-list1").style.display = "none";
            document.getElementById("nav-list2").style.display = "none";
            document.getElementById("nav-list3").style.display = "none";
            document.querySelector("header").style.width = "auto";
            document.querySelector("header").style.transition = "width 2s ease-in";
            close.style.display = "none";
            hamburguer.style.display = "block"
        })

        hamburguer.addEventListener("click", ()=> {
            document.getElementById("nav-list1").style.display = "flex";
            document.getElementById("nav-list2").style.display = "flex";
            document.getElementById("nav-list3").style.display = "flex";
            document.querySelector("header").style.width = "";
            document.querySelector("header").style.transition = "width 2s ease-in";
            close.style.display = "block";
            hamburguer.style.display = "none";
        });
       
        const buttonSave = document.getElementById("btnSave");

        buttonSave.addEventListener("click", (e) => {
            let newtask = document.getElementById("newtask").value;
            if (newtask == ""){
                e.preventDefault();
                document.getElementById('errorMsg').style.display = 'block';
            } else{
                document.getElementById('errorMsg').style.display = 'none';
                return true;
            } 
        });

    document.addEventListener("change", function(e){
    if(e.target.classList.contains("check-tarefa")){
        const li = e.target.closest("li");
        const id = li.dataset.id;

        const form = document.createElement("form");
        form.method = "POST";
        form.innerHTML = `
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="mudar" value="1">
        `;
        document.body.appendChild(form);
        form.submit();
    }
    });

    document.addEventListener("DOMContentLoaded", () => {
        const msgSuccess = document.getElementById("msgSuccess");
        if (msgSuccess) {
            setTimeout(() => msgSuccess.remove(), 5000);
        }
    });