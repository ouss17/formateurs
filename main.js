var objForm = document.getElementById('objForm');
var cross = document.getElementById('cross');
var formAdd = document.querySelector('.form-add');
var excelFile = document.getElementById('excel-file');

if (cross !== null) {
    //Faire monter le formulaire (pour le cacher)
    cross.addEventListener('click', function () {
        formAdd.style.top = '-6000px';
        formAdd.style.position = 'absolute';
    });

    //Faire descendre le formulaire (pour le montrer)
    objForm.addEventListener('click', function () {
        formAdd.style.top = 0;
        formAdd.style.position = 'relative';
    });
}
var formUpload = document.getElementById('form-upload');
var moveUpload = document.querySelector('.move-upload');
var submitFile = document.getElementById('submit-file');

//Définir le seul format de fichier déposable autorisé (.xlsx)
$('#excel-file').change(function () {
    var filepath = this.value;
    var m = filepath.match(/([^\/\\]+)$/);
    var filename = m[1];
    $('#filename').text(filename);
});


//Empêcher l'utilisateur de chercher une ville et un code postal simultanémment pour éviter des recherches illogiques
$("#search-form").css('opacity', '0');
$('#search-form').css('pointer-events', 'none');
document.addEventListener("click", function () {
    if ($('#city').val() !== null) {
        if ($('#city').val() !== "Ville") {
            $('#depart').css('pointer-events', 'none');
            $('#depart').css('background-color', 'darkgrey');
        } else if ($('#city').val() == "Ville") {
            $('#depart').css('pointer-events', 'auto');
            $('#depart').css('background-color', '#2880ca');
        }
    }
    if ($('#depart').val() !== null) {
        if ($('#depart').val() !== "Département") {
            $('#city').css('pointer-events', 'none');
            $('#city').css('background-color', 'darkgrey');
        } else if ($('#depart').val() == "Département") {
            $('#city').css('pointer-events', 'auto');
            $('#city').css('background-color', '#2880ca');
        }
    }

    if ($('#titleFormation').val() !== null) {
        if ($('#titleFormation').val() !== "Formation") {
            $('#formation').css('pointer-events', 'none');
            $('#formation').css('background-color', 'darkgrey');
        } else if ($('#titleFormation').val() == "Formation") {
            $('#formation').css('pointer-events', 'auto');
            $('#formation').css('background-color', '#2880ca');
        }
    }

    if ($('#formation').val() !== null) {
        if ($('#formation').val() !== "Sous Formation") {
            $('#titleFormation').css('pointer-events', 'none');
            $('#titleFormation').css('background-color', 'darkgrey');
        } else if ($('#formation').val() == "Sous Formation") {
            $('#titleFormation').css('pointer-events', 'auto');
            $('#titleFormation').css('background-color', '#2880ca');
        }
    }

    if ($('#depart').val() == "Département" && $('#city').val() == "Ville" && $('#formation').val() == "Sous Formation" && $('#titleFormation').val() == "Formation") {
        $("#search-form").css('opacity', '0');
        $('#search-form').css('pointer-events', 'none');
    } else if ($('#depart').val() == null && $('#city').val() == null && $('#formation').val() == null && $('#titleFormation').val() == null) {
        $("#search-form").css('opacity', '0');
        $('#search-form').css('pointer-events', 'none');
    } else {
        $("#search-form").css('opacity', '1');
        $('#search-form').css('pointer-events', 'auto');
    }

});

// Faire apparaître un formulaire d'ajout de formateur en tapant 'fm3sn'
var easter = false
document.addEventListener('keyup', (key) => {
    if (key.keyCode === 70) {
        easter = true;
        document.addEventListener('keyup', (key) => {
            if (key.keyCode === 77) {
                easter = true;
                document.addEventListener('keyup', (key) => {
                    if (key.keyCode === 99) {
                        easter = true;
                        document.addEventListener('keyup', (key) => {
                            if (key.keyCode === 83) {
                                easter = true;
                                document.addEventListener('keyup', (key) => {
                                    if (key.keyCode === 78) {
                                        easter = true;
                                        console.log("gagné");
                                        document.getElementById('add-formateur').style.top = 0;
                                        document.getElementById('add-formateur').style.position = "relative";
                                    } else {
                                        easter = false;
                                    }
                                });
                            } else {
                                easter = false;
                            }
                        });
                    } else {
                        easter = false;
                    }
                });
            } else {
                easter = false;
            }
        });
    } else {
        easter = false;
    }


});

// Se rendre sur la page des formations en tapant 'page formateur'
var easter2 = false
document.addEventListener('keyup', (key) => {
    if (key.keyCode === 80) {
        easter2 = true;
        document.addEventListener('keyup', (key) => {
            if (key.keyCode === 65) {
                easter2 = true;
                document.addEventListener('keyup', (key) => {
                    if (key.keyCode === 71) {
                        easter2 = true;
                        document.addEventListener('keyup', (key) => {
                            if (key.keyCode === 69) {
                                easter2 = true;
                                document.addEventListener('keyup', (key) => {
                                    if (key.keyCode === 70) {
                                        easter2 = true;
                                        document.addEventListener('keyup', (key) => {
                                            if (key.keyCode === 79) {
                                                easter2 = true;
                                                document.addEventListener('keyup', (key) => {
                                                    if (key.keyCode === 82) {
                                                        easter2 = true;
                                                        document.addEventListener('keyup', (key) => {
                                                            if (key.keyCode === 77) {
                                                                easter2 = true;
                                                                document.addEventListener('keyup', (key) => {
                                                                    if (key.keyCode === 65) {
                                                                        easter2 = true;
                                                                        document.addEventListener('keyup', (key) => {
                                                                            if (key.keyCode === 84) {
                                                                                easter2 = true;
                                                                                document.addEventListener('keyup', (key) => {
                                                                                    if (key.keyCode === 69) {
                                                                                        easter2 = true;
                                                                                        document.addEventListener('keyup', (key) => {
                                                                                            if (key.keyCode === 85) {
                                                                                                easter2 = true;
                                                                                                document.addEventListener('keyup', (key) => {
                                                                                                    if (key.keyCode === 82) {
                                                                                                        easter2 = true;
                                                                                                        document.location.href = "formations.php";
                                                                                                    } else {
                                                                                                        easter2 = false;
                                                                                                    }
                                                                                                });
                                                                                            } else {
                                                                                                easter2 = false;
                                                                                            }
                                                                                        });
                                                                                    } else {
                                                                                        easter2 = false;
                                                                                    }
                                                                                });
                                                                            } else {
                                                                                easter2 = false;
                                                                            }
                                                                        });
                                                                    } else {
                                                                        easter2 = false;
                                                                    }
                                                                });
                                                            } else {
                                                                easter2 = false;
                                                            }
                                                        });
                                                    } else {
                                                        easter2 = false;
                                                    }
                                                });
                                            } else {
                                                easter2 = false;
                                            }
                                        });
                                    } else {
                                        easter2 = false;
                                    }
                                });
                            } else {
                                easter2 = false;
                            }
                        });
                    } else {
                        easter2 = false;
                    }
                });
            } else {
                easter2 = false;
            }
        });
    } else {
        easter2 = false;
    }


});
var ht = document.getElementById("block");

var centerButton = document.getElementById("center-button");
var centerInput = document.getElementById("center-input");
centerButton.addEventListener("click", () => {
    if (centerInput.value.trim() !== '' && isNaN(centerInput.value)) {
        ht.style.pointerEvents = "none";
        let lonCenter, latCenter;
        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q=' + centerInput.value + ', France ', function (data) {

            //ADD COORDINATES TO A TAB IF EXIST ELSE COORDINATES'LL BE NULLED
            if (data && data.length) {
                lonCenter = data[0].lon;
                latCenter = data[0].lat;
            } else {
                lonCenter = null;
                latCenter = null;
            }
        });
        setTimeout(() => {
            ht.style.pointerEvents = "auto";
            if (lonCenter !== null || latCenter !== null) {
                let http = new XMLHttpRequest();
                http.open("GET", "truncated.php", true);
                http.send();
                let xml = new XMLHttpRequest();

                setTimeout(() => {

                    //SEND DATAS PARAMETERS
                    xml.open("GET", "getCenter.php?adresse=" + centerInput.value + "&lat=" + latCenter + "&lon=" + lonCenter, true);
                    xml.send();
                    //RELOAD PAGE
                    setTimeout(() => {
                        document.location.href = "index.php";
                    }, 400)
                }, 200);
            } else {

            }
        }, 100);
    } else {
        alert('Veuillez entrer une adresse valide !');
    }
});