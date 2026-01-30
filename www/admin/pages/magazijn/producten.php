<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<div class="container mt-5">

    <div class="container mt-5">
        <!-- Navbar met knoppen  --><div class="row"></div>
        <nav class="navbar navbar-expand-lg navbar-light mb-4" style="background-color: #FFD700;">
            <a class="navbar-brand" href="#">Producten Beheer</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="producten.php" class="btn btn-secondary mb-2">Terug naar magazijn</a>
                    </li>
                    <li class="nav-item">
                        <a href="insert_product.php" class="btn btn-success mb-2 ml-2">Nieuw Product</a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_supplier.php" class="btn btn-info mb-2 ml-2">Leveranciersbeheer</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Zoekformulier in een card -->
        <div class="card mb-4">
            <div class="card-header">
                Zoek en Filter
            </div>
            <div class="card-body">
                <form class="form-inline">
                    <div class="form-group mb-2 mr-sm-2">
                        <input type="text" name="search" class="form-control" placeholder="Zoek product..." value="">
                    </div>
                    <input type="hidden" name="items_per_page" value="25">
                    <button type="submit" class="btn btn-primary mb-2">Zoeken</button>
                    <a href="producten.php" class="btn btn-secondary mb-2 ml-2">Reset</a>
                </form>
                <a href="manage_stock.php" class="btn btn-warning mt-3">Voorraad Beheren</a>
            </div>
        </div>

        <!-- Paginering boven de regel "per pagina en totaal producten" -->
        <div class="row mb-3">
            <div class="col-md-12 text-right">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0 justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="?page=1&search=&items_per_page=25">Eerste</a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="?page=0&search=&items_per_page=25">Vorige</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="?page=1&search=&items_per_page=25">1</a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link" href="?page=2&search=&items_per_page=25">2</a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link" href="?page=3&search=&items_per_page=25">3</a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link" href="?page=2&search=&items_per_page=25">Volgende</a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link" href="?page=4&search=&items_per_page=25">Laatste</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Regel "per pagina en totaal producten" -->
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <form class="form-inline">
                    <label for="items_per_page" class="mr-2">Toon</label>
                    <select name="items_per_page" id="items_per_page" class="form-control mb-2 mr-sm-2" onchange="this.form.submit()">
                        <option value="10" >10</option>
                        <option value="25" selected>25</option>
                        <option value="50" >50</option>
                        <option value="100" >100</option>
                    </select>
                    <label for="items_per_page" class="mr-2">producten per pagina</label>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <p class="mr-3 mb-0"><strong>1 - 25 van 86 producten</strong></p>
            </div>
        </div>

        <!-- Producten Tabel -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Producten Overzicht</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Titel</th>
                        <th>Afbeelding</th>
                        <th>Inkoopprijs excl. BTW</th>
                        <th>Inkoopprijs incl. BTW</th>
                        <th>Aantal verpakt</th>
                        <th>Voorraad</th>
                        <th>Acties</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>20230087</td>
                        <td>parfum Angel</td>
                        <td><img src="product_img/20240322_110610.jpg" alt="parfum Angel" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €10.25</td>
                        <td>€12.40</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230087" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230087" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230087" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230086</td>
                        <td>kaars potje</td>
                        <td><img src="product_img/IMG_1299 2024-03-19 20_09_16.JPG" alt="kaars potje" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €6.68</td>
                        <td>€8.08</td>
                        <td>12</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230086" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230086" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230086" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230085</td>
                        <td>Waxinepitje met pitvoet (100mm) 25st</td>
                        <td><img src="product_img/no_picture.jpg" alt="Waxinepitje met pitvoet (100mm) 25st" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €2.83</td>
                        <td>€3.43</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230085" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230085" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230085" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230084</td>
                        <td>Geurolie Monkey Farts 20ml</td>
                        <td><img src="product_img/no_picture.jpg" alt="Geurolie Monkey Farts 20ml" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €2.90</td>
                        <td>€3.51</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230084" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230084" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230084" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230083</td>
                        <td>Geurolie Amber 20ml</td>
                        <td><img src="product_img/no_picture.jpg" alt="Geurolie Amber 20ml" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €3.38</td>
                        <td>€4.09</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230083" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230083" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230083" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230082</td>
                        <td>The Candlesshop Colletion Geurolie Pumpkin Halloween 20ml</td>
                        <td><img src="product_img/no_picture.jpg" alt="The Candlesshop Colletion Geurolie Pumpkin Halloween 20ml" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €2.56</td>
                        <td>€3.10</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230082" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230082" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230082" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230081</td>
                        <td>Geurolie Cashmere Woods 20ml</td>
                        <td><img src="product_img/no_picture.jpg" alt="Geurolie Cashmere Woods 20ml" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €3.07</td>
                        <td>€3.72</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230081" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230081" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230081" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230080</td>
                        <td>Geurolie Good Habits Sakura 20ml (diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Geurolie Good Habits Sakura 20ml (diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €2.69</td>
                        <td>€3.26</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230080" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230080" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230080" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230079</td>
                        <td>Kleur pil Honey</td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Honey" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230079" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230079" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230079" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230078</td>
                        <td>Kleur pil Midnight Blue </td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Midnight Blue " class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230078" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230078" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230078" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230077</td>
                        <td>Kleur pil Oranje </td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Oranje " class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230077" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230077" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230077" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230076</td>
                        <td>Kleur pil zonnegeel </td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil zonnegeel " class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230076" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230076" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230076" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230075</td>
                        <td>Stearine 500gr </td>
                        <td><img src="product_img/no_picture.jpg" alt="Stearine 500gr " class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €2.90</td>
                        <td>€3.51</td>
                        <td>0</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230075" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230075" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230075" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230074</td>
                        <td>Parafine 1 kg </td>
                        <td><img src="product_img/no_picture.jpg" alt="Parafine 1 kg " class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €4.74</td>
                        <td>€5.74</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230074" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230074" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230074" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230073</td>
                        <td>Kleur pil Wit(diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Wit(diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230073" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230073" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230073" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230072</td>
                        <td>Kleur pil Donker blauw(diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Donker blauw(diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230072" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230072" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230072" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230071</td>
                        <td>Kleur pil Zonne geel(diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Zonne geel(diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230071" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230071" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230071" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230070</td>
                        <td>Kleur pil Licht groen(diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil Licht groen(diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230070" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230070" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230070" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230069</td>
                        <td>Kleur pil kerstrood(diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Kleur pil kerstrood(diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €0.65</td>
                        <td>€0.79</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230069" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230069" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230069" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230068</td>
                        <td>Stearine 500gr (Diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Stearine 500gr (Diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €5.80</td>
                        <td>€7.02</td>
                        <td>0</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230068" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230068" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230068" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230067</td>
                        <td>Parafine 1 kg (Diane)</td>
                        <td><img src="product_img/no_picture.jpg" alt="Parafine 1 kg (Diane)" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €4.74</td>
                        <td>€5.74</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230067" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230067" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230067" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230066</td>
                        <td>haarborstel</td>
                        <td><img src="product_img/20240217_111946.jpg" alt="haarborstel" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €1.20</td>
                        <td>€1.45</td>
                        <td>1</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230066" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230066" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230066" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230065</td>
                        <td>epoxyhars sieraden flessen</td>
                        <td><img src="product_img/" alt="epoxyhars sieraden flessen" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €68.30</td>
                        <td>€82.64</td>
                        <td>600</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230065" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230065" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230065" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230064</td>
                        <td>epoxyhars voeding flessen</td>
                        <td><img src="product_img/" alt="epoxyhars voeding flessen" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €27.31</td>
                        <td>€33.05</td>
                        <td>1600</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230064" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230064" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230064" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    <tr>
                        <td>20230063</td>
                        <td>Grote ronde logo stickers</td>
                        <td><img src="product_img/" alt="Grote ronde logo stickers" class="img-thumbnail" style="width: 75px; height:75px;"></td>
                        <td>                                €47.88</td>
                        <td>€57.94</td>
                        <td>120</td>
                        <td>0</td>
                        <td>
                            <a href="edit_product.php?sku=20230063" class="btn btn-warning btn-sm">Bewerken</a>
                            <a href="delete_product.php?sku=20230063" class="btn btn-danger btn-sm">Verwijderen</a>
                            <a href="view_product.php?sku=20230063" class="btn btn-info btn-sm">Bekijken</a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Paginering en totaal aantal producten onderaan de tabel -->
                <div class="row mb-3">
                    <div class="col-md-6 text-left">
                        <p class="mb-0"><strong>1 - 25 van 86 producten</strong></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <nav>
                            <ul class="pagination mb-0 justify-content-end">
                                <li class="page-item disabled">
                                    <a class="page-link" href="?page=1&search=&items_per_page=25">Eerste</a>
                                </li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="?page=0&search=&items_per_page=25">Vorige</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="?page=1&search=&items_per_page=25">1</a>
                                </li>
                                <li class="page-item ">
                                    <a class="page-link" href="?page=2&search=&items_per_page=25">2</a>
                                </li>
                                <li class="page-item ">
                                    <a class="page-link" href="?page=3&search=&items_per_page=25">3</a>
                                </li>
                                <li class="page-item ">
                                    <a class="page-link" href="?page=2&search=&items_per_page=25">Volgende</a>
                                </li>
                                <li class="page-item ">
                                    <a class="page-link" href="?page=4&search=&items_per_page=25">Laatste</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
?>