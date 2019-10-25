jQuery(function() {

    // Soumission du formlaire "onchange" à la sélection d'un fichier
    $('#fileInput').on('change', function() {
        var files = $("#fileInput")[0].files;
        $('#fileInputLabel').html(files[0].name);
        $("form#upForm").submit();

    })

    // Soumission du formlaire "onchange" à la 
    // sélection d'une nouvelle valeur de précision
    $("#accuracy").on('change', function() {
        // submitupForm;
        if ($("#fileInput")[0].files.length) {
            $("form#upForm").submit();
        }


    });

    // Soumission du formulaire ; appel AJAX
    $("form#upForm").submit(function() {

        $(".loader12").css({ 'display': 'inline-block' });
        var form = $("#upForm");

        // Serializing all For Input Values (not files!) in an 
        // Array Collection so that we can iterate this collection later.
        var params = form.serializeArray();

        // Getting Files Collection
        var files = $("#fileInput")[0].files;

        // Declaring new Form Data Instance  
        var formData = new FormData();

        // Now Looping the parameters for all form input fields and assigning them as Name Value pairs. 
        $(params).each(function(index, element) {
            formData.set(element.name, element.value);
        });
        // Looping through uploaded files collection in case there is a Multi File Upload. 
        // This also works for single i.e simply remove MULTIPLE attribute from file control in HTML.  
        for (var i = 0; i < files.length; i++) {
            if (files[i].type != 'application/vnd.google-earth.kml+xml') {
                alert('The file must be a valid kml file !, type = ' + files[i].type);
                $(".loader12").css({ 'display': 'none' });
                return false;
            }
            formData.set('fichier', files[i], files[i].name);
        }

        $.ajax({
            url: 'ajax_controller.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                //console.log('data = ' + data);
                $('#resultat').html(data);
                $('#container_resultat').removeClass('d-none');
                $(".loader12").css({ 'display': 'none' });
            },
            error: function(xhr, textStatus, errorThrown) {
                //console.log(error);
                $('#resultat').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + xhr.responseText + '</div>');
                $(".loader12").css({ 'display': 'none' });
            }
        });

        return false;
    });

    // Changement de langue
    $('#langSelect').on('change', function() {
        $("form#langForm").submit();

    })

});

// récupère la chaine modifiée et prépare un nouveau fichier à sauvegarder
var createAndOpenFile = function() {
    var xmltext = b64_to_utf8($("#xmlString").val());
    var reducedFileName = $("#reducedFileName").val();
    /**/
    var pom = document.createElement('a');

    var filename = reducedFileName + ".kml";
    var pom = document.createElement('a');
    var bb = new Blob([xmltext], { type: 'application/vnd.google-earth.kml+xml kml' });

    pom.setAttribute('href', window.URL.createObjectURL(bb));
    pom.setAttribute('download', filename);

    pom.dataset.downloadurl = ['application/vnd.google-earth.kml+xml kml', pom.download, pom.href].join(':');
    pom.draggable = true;
    pom.classList.add('dragout');

    pom.click();
}

// base64_decode
var b64_to_utf8 = function(str) {
    return decodeURIComponent(escape(window.atob(str)));
}