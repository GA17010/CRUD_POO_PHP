<!-- Boton para Desktop -->
<p class="has-text-right is-hidden-mobile pt-4 pb-4">
    <a href="#" class="button is-link is-rounded btn-back"><i class="fas fa-arrow-left"></i>
        <span class="ml-2">Volver</span>    
    </a>
</p>
<!-- Icono para mobile -->
<p class="has-text-right btn_mobile is-hidden-tablet pt-4 pb-4">
    <a href="#" class="button is-small is-link is-rounded btn-back"><i class="fas fa-arrow-left"></i></a>
</p>


<script type="text/javascript">
    let btn_back = document.querySelector(".btn-back");

    btn_back.addEventListener('click', function(e){
        e.preventDefault();
        window.history.back();
    });
</script>