<?PHP
        function connexion() {
                $bdd = mysqli_connect('localhost', 'root', '', 'padel');
                if (!$bdd) {
                        echo("Erreur de connexion : " . mysqli_connect_error());
                }
                return $bdd;
        }
?>
