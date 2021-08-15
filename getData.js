var ht = document.getElementById("block");

//UPLOAD FICHIER XLSX
function uploadXlsx() {
    var files = document.getElementById("excel-file").files;

    // Si le fichier à été déposé
    if (files.length > 0) {
        var formData = new FormData();
        formData.append("excelFile", files[0]);

        // Ajax de l'upload : communication avec php pour effectuer des requetes sql et enregistrement de fichier
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "getUpload.php", true);
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText;
                if (response == 1) {
                    // Si l'upload a bien été effectué
                    alert("Upload successfully.");
                } else {
                    alert("File not uploaded.");
                }
            }
        };
        // Envoie de la méthode ajax
        xhttp.send(formData);
    } else {
        alert("Please select a file");
    }
}
var tabAdressage;
var file;
var fil2e;

// fonction de lecture du fichier excel
var ExcelToJSON = function () {

    this.parseExcel = function (file) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var data = e.target.result;
            file = data;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            workbook.SheetNames.forEach(function (sheetName) {
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                var json_object = JSON.stringify(XL_row_object);
                tabAdressage = JSON.parse(json_object);
            })
        };

        reader.onerror = function (ex) {
            // console.log(ex);
        };

        reader.readAsBinaryString(file);
    };
};

// Fonction d'envoi des données du fichier excel à php
function handleFileSelect(evt) {

    var files = evt.target.files; // FileList object
    fil2e = files;
    var xl2json = new ExcelToJSON();
    xl2json.parseExcel(files[0]);
    //appel de la fonction process (en bas)
    process();
}

if (document.getElementById('excel-file') !== null) {
    document.getElementById('excel-file').addEventListener('change', handleFileSelect, false);
}

//envoi des données du fichier excel
function process() {
    setTimeout(function () {

        var lon, lat;

        //requete sur l'api de la carte toutes les secondes
        function apiRequest(adresse, ville, zip1, zip2) {
            $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q=' + adresse + ' ' + zip1 + zip2 + ' ' + ville + ', France ', function (data) {

                //Envoi des coordonées trouvées par l'api si elles existent sinon les définir à null pour éviter des erreurs sql
                if (data && data.length) {
                    lon = data[0].lon;
                    lat = data[0].lat;
                } else {
                    lon = null;
                    lat = null;
                }
            });
        }


        setTimeout(function () {

            //Ajouter tous les formateurs (variable g sert à passer d'une personne à une autre)
            var g = 0;
            do {
                task(g);
                g++;
            } while (g <= tabAdressage.length);

            //Envoi des données du fichier (définies à null si elles sont vides)
            function apiSendToPhp(firstName, lastName, email, job, city, inter, postal1, postal2, id, date, adresse, num, lat, lon) {
                if (firstName === undefined) {
                    firstName = null
                }
                if (lastName === undefined) {
                    lastName = null
                }
                if (email === undefined) {
                    email = null
                }
                if (job === undefined) {
                    job = null
                }
                if (city === undefined) {
                    city = null
                }
                if (inter === undefined) {
                    inter = null
                }
                if (postal1 === undefined) {
                    postal1 = null
                }
                if (postal2 === undefined) {
                    postal2 = null
                }
                if (date === undefined) {
                    date = null
                }
                if (adresse === undefined) {
                    adresse = null
                }
                if (num === undefined) {
                    num = null
                }

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.response);
                    }
                }

                //Envoi à getData.php
                xmlhttp.open("GET", "getData.php?firstName=" + firstName + "&lastName=" + lastName + "&email=" + email + "&job=" + job.toLowerCase() + "&city=" + city + "&inter=" + inter + "&postal1=" + postal1 + "&postal2=" + postal2 + "&id=" + id + "&date=" + date + "&adresse=" + adresse + "&num=" + num + "&lat=" + lat + "&lon=" + lon, true);
                xmlhttp.send();
            };

            //Fonction pour vider la base de données avant le rajout d'un fichier
            function truncate() {
                let http = new XMLHttpRequest();
                http.open("GET", "truncate.php", true);
                http.send();
            };

            //APPLICATION
            function task(g) {
                truncate();

                //SET TIMEOUT FOR THE MAP API
                setTimeout(function () {
                    apiRequest(tabAdressage[g].Adresse, tabAdressage[g].Ville, tabAdressage[g].Departement, tabAdressage[g].Zip, tabAdressage[g].Nom, tabAdressage[g].Prenom);

                    //Attendre que l'envoie des données au fichier php soit terminé
                    setTimeout(function () {
                        apiSendToPhp(tabAdressage[g].Nom, tabAdressage[g].Prenom, tabAdressage[g].Mail, tabAdressage[g].Specialite, tabAdressage[g].Ville, tabAdressage[g].Intervention, tabAdressage[g].Departement, tabAdressage[g].Zip, tabAdressage[g].Id, tabAdressage[g].Date, tabAdressage[g].Adresse, tabAdressage[g].Tel, lat, lon);
                    }, 500);
                    //Impossibilité d'action sur la page le temps de l'envoi pour éviter des conflits ou erreurs
                    ht.style.pointerEvents = "none";
                    var wait = document.getElementById('wait');
                    wait.innerHTML = "Veuillez patienter le temps de l'import en base de donnnées..."
                    // }
                }, 1001 * g);
            }

            //Une fois tous les utilisateurs ajoutés, rechargement de la page avec fichier mis à jour
            setTimeout(() => {
                ht.style.pointerEvents = "auto";
                wait.innerHTML = "Chargement de la page..."
                uploadXlsx();

                //RELOAD PAGE
                setTimeout(() => {
                    document.location.href = "index.php";
                }, 400)
            }, (tabAdressage.length + 1) * 1001);
        }, 1020);
    }, 2000);


};

// Ajout d'un centre de formation
let centerBut = document.getElementById("center-button");
let centerInp = document.getElementById("center-input");
centerBut.addEventListener("click", () => {
    //Si l'adresse entrée est valable
    if (centerInp.value.trim() !== '' && isNaN(centerInp.value)) {
        ht.style.pointerEvents = "none";
        let lonCenter, latCenter;
        // API MAP requete
        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q=' + centerInp.value + ', France ', function (data) {

            //Envoi des données du fichier (définies à null si elles sont vides)
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

                    //Envoi sur getCenter.php
                    xml.open("GET", "getCenter.php?adresse=" + centerInp.value + "&lat=" + latCenter + "&lon=" + lonCenter, true);
                    xml.send();
                    //Rechargement de la page
                    setTimeout(() => {
                        document.location.href = "index.php";
                    }, 400)
                }, 200);
            } else {

            }
        }, 100);
    }else{
        alert('Veuillez entrer une adresse valide !');
    }
});
