<?php

if (!empty($_GET['city'])) {
    $city = str_replace(' ', '-', strip_tags($_GET['city']));
    $json = getWeather($city);

    if (isset($json->status)) {
        echo 'city not found!'; die;
    }

    $city   = trim(explode(', ', $json->region)[0]);
    $region = trim(str_replace($city . ',', '', $json->region));
}

function getWeather($city) {

    $url  = 'https://weatherdbi.herokuapp.com/data/weather/' . $city;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Weather</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <form>
                <input type="text" name="city" value="<?php echo (isset($city)) ? $city : null; ?>" placeholder="Insert a city..." autocomplete="off">
            </form>

            <?php if (isset($city)) { ?>
            <div class="card">
                <div class="row">
                    <div class="icon">
                        <img src="<?php echo $json->currentConditions->iconURL; ?>" title="<?php echo $json->currentConditions->comment; ?>">
                    </div>
                    <div class="city">
                        <div><?php echo $city; ?></div>
                        <div class="region"><?php echo $region; ?></div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="detail">
                        <div class="weather"><span><?php echo $json->currentConditions->comment; ?></span></div>
                        <div><span>Wind:</span> <?php echo $json->currentConditions->wind->km; ?>km/h</div>
                        <div><span>Humidity:</span> <?php echo $json->currentConditions->humidity; ?></div>
                        <div><span>precipitation:</span> <?php echo $json->currentConditions->precip; ?></div>
                    </div>
                    <div class="temperature">
                        <?php echo $json->currentConditions->temp->c; ?>°
                    </div>
                </div>

                <table>
                    <tr>
                        <?php foreach (array_slice($json->next_days, 0, 7) as $day) { ?>
                        <td><?php echo strtoupper(substr($day->day, 0, 3)); ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach (array_slice($json->next_days, 0, 7) as $day) { ?>
                        <td><img src="<?php echo $day->iconURL; ?>"  title="<?php echo $day->comment; ?>"></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach (array_slice($json->next_days, 0, 7) as $day) { ?>
                        <td><?php echo $day->max_temp->c; ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach (array_slice($json->next_days, 0, 7) as $day) { ?>
                        <td><?php echo $day->min_temp->c; ?></td>
                        <?php } ?>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
    </body>
</html>