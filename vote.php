<?php

try
{
    $dns="mysql:host=localhost; dbname=glasovanje";
    $username="root";
    $password="root";

    $pdo=new PDO($dns, $username, $password);
}
catch(PDOException $e)
{
    echo "Error";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h>GLASAJTE ZA SVOG FAVORITA</h>
    <br>
    <form action="" method="get">
        Izaberite kategoriju:
        <select name="kategorija">
            <option value="knjiga">Knjiga</option>
            <option value="film">Film</option>
            <option value="igra">Igra</option>
        </select>
        <br>
        Unesi broj glasova:
        <input type="number" name="glas" min="1" value="1">
        <br>
        <input type="submit" name="submit" value="GLASAJ!">
    </form>
</body>
</html>

<?php

try
{
    if (!empty($_GET))
    {
        $kategorija=$_GET["kategorija"];
        $brojGlasova=$_GET["glas"];

        $stmt=$pdo->query("SELECT * FROM glasanje");

        while ($row=$stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            $provjera=FALSE; 

            foreach ($row as $zapis)
            {
                if (in_array($kategorija, $zapis))
                {
                    $glas=$zapis["glas"];
                    $glas+=$brojGlasova;

                    $provjera=TRUE;

                    $sql=("UPDATE glasanje SET glas=:glas WHERE kategorija=:kategorija LIMIT 1");
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(":kategorija", $kategorija);
                    $stmt->bindParam(":glas", $glas);
                    $stmt->execute();
                    break; 
                }
            }
        
            if ($provjera===FALSE)
            {
                $sql=("INSERT INTO glasanje (kategorija, glas) VALUES (:kategorija, :brojGlasova)");
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(":kategorija", $kategorija);
                $stmt->bindParam(":brojGlasova", $brojGlasova);
                $stmt->execute();
            }   
        }

       
        echo "Uspješno ste glasali";
    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
}


?>