<?php
require 'classes.php';

$title = 'Sieci neuronowe';
?>

<!DOCTYPE html>

<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="public/bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="public/css/style.css" />
        <script type="text/javascript" src="public/jquery/jquery-1.11.2.js"></script>
        <script type="text/javascript" src="public/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="public/js/script.js"></script>
    </head>
    <body>

        <div class="container-fluid">

            <div class="container">

                <?php if (isset($_GET['error'])): ?>
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4">
                            <h1 style="color: red;">Wpisuj tylko wartości liczbowe.</h1>
                        </div>
                    </div>
                <?php endif; ?>

                <div id="header" class="row">
                    <div class="col-sm-4 col-sm-offset-4"><h2>Model neuronu Hebba</h2></div>
                </div>



                <div class="row">

                    <div class="col-sm-4 col-sm-offset-4">
                        <h3>Podaj dane wejściowe:</h3>
                    </div>

                </div>

                <div id="input-data" class="row">

                    <div class="col-sm-4 col-sm-offset-4">
                        <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="number_x">Podaj liczbę wektorów wejściowych:</label>
                                <input name="number_x" type="text" class="form-control" id="number_x" required="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="number_indexes">Podaj liczbę indeksów wektora wejściowego:</label>
                                <input name="number_indexes" type="text" class="form-control" id="number_indexes" required="" autocomplete="off">
                            </div>


                            <button type="submit" class="btn btn-default">Zatwierdź</button>
                        </form>
                    </div>
                </div>
                <?php if (isset($number_x) && isset($number_indexes)): ?>

                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4" style="margin-bottom: 10px; margin-top: 10px;">
                            <h3>Wypełnij wektory wejściowe danymi liczbowymi:</h3>
                        </div>
                    </div>

                    <div id="content" class="row">

                        <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                            <div class="col-sm-12">

                                <div class="row">
                                    <div class="col-sm-4 col-sm-offset-4">

                                        <div class="form-group">
                                            <label for="const_learn">Wpisz stałą uczenia(domyślnie 1):</label>
                                            <input name="const_learn" type="text" class="form-control" id="const_learn" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label for="treshold">Wektor x0 - wartość progowa (opcjonalnie):</label>

                                            <input name="x0" type="text" class="form-control" autocomplete="off">                             

                                        </div>

                                        <div class="form-group">
                                            <label for="treshold"><span style="color: #cc0000;">*</span>Wektor wag:</label>
                                            <?php for ($x = 0; $x < $number_indexes; $x++): ?>
                                                <input name="<?php echo 'scales' . '_' . ($x + 1); ?>" type="text" class="form-control" autocomplete="off" required="">
                                            <?php endfor; ?>

                                        </div>

                                    </div>
                                </div>


                                <div class="row">
                                    <?php for ($i = 0; $i < $number_x; $i++): ?>
                                        <div class="col-sm-3">
                                            <label for="x<?php echo '' . ($i + 1); ?>"><span style="color: #cc0000;">*</span>Wektor <?php echo 'x' . ($i + 1); ?></label>
                                            <?php for ($j = 0; $j < $number_indexes; $j++): ?>
                                                <input name="<?php echo 'x' . ($i + 1) . '_' . ($j + 1); ?>" type="text" class="form-control" required="" autocomplete="off">
                                            <?php endfor; ?>

                                        </div>
                                    <?php endfor; ?>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4 col-sm-offset-4">
                                        <label for="function">Wybierz funkcję aktywacji:</label>
                                        <select class="form-control" name="function" id="function">
                                            <option value="signum">Funkcja signum</option>
                                            <option value="sigmoidal">Funkcja sigmoidalna bipolarna</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2 col-sm-offset-5" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            <input type="hidden" name="count" value="<?php echo $number_indexes; ?>" />
                                            <button name="vectors" type="submit" class="btn btn-default">OBLICZ</button>
                                        </div>
                                    </div>

                                </div>



                            </div>

                        </form>

                    </div>
                <?php endif; ?>


                <!--wynik-->
                <div id="solution" class="row">

                    <div class="col-sm-4 col-sm-offset-4">

                    </div>

                </div>

                <div class="row" id="footer">
                    <div class="col-sm-10 col-sm-offset-1">

                    </div>

                </div>

            </div>
        </div>

        <?php if (isset($totals)): ?>
            <script>

                $(document).ready(function () {

                    function ModalWindow(settings)
                    {
                        var css = {
                            position: 'absolute',
                            width: settings.width,
                            'min-height': settings.height,
                            top: settings.top,
                            left: settings.left,
                            background: '#ffffff',
                            'z-index': '9999',
                            'border-radius': '5px',
                            overflow: 'hidden',
                            padding: '10px',
                            'box-shadow': '0px 0px 0px 8px rgba(0,0,0,0.3)'
                        };
                        var overlay = '<div class="row" id="overlay"></div>';
                        var append = '<div id="modal-window"></div>';
                        var row = '<div id="row" class="row" id="content"></div>';
                        var col = '<div id="col" class="col-sm-12"></div>';
                        var close = '<div class="row"><div class="col-sm-1 col-sm-offset-11" style="text-align:right;">' +
                                '<a id="close" class="btn btn-default">X</a>' +
                                '</div></div>';

                        this.show = function ()
                        {
                            $('body').prepend(overlay);
                            $('body').prepend(append);
                            $('#modal-window').css(css);
                            $('#modal-window').append(row);
                            $('#row').append(col);
                            $('#col').append(close);

                        };

                        this.close = function ()
                        {
                            $('#modal-window').css('display', 'none');
                            $('#overlay').css('display', 'none');
                        };

                        this.insert = function (html) {
                            $('#col').append(html);
                        };

                    }

                    var modal;
                    var html = '<div class="row"><div id="totals" class="col-sm-12" style="text-align:center;"></div></div>';

                    modal = new ModalWindow({width: '60%', top: '10%', left: '20%', 'min-height': '20%'});
                    modal.show();
                    modal.insert(html);

                    var data = '<?php echo $totals; ?>';
                    var string = '<h2>Wyniki:</h2>' + '<p>' + data + '</p>';
                    $('#totals').append(string);

                    $('body').on('click', '#close', function (e) {
                        e.preventDefault();
                        modal.close();
                        window.location.href = 'dummy.php';
                    });

                });

            </script>
        <?php endif; ?>


    </body>
</html>

