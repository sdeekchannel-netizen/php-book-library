<?php
session_start();

$books = isset($_SESSION["books"]) ? $_SESSION["books"] : [
    [
        "numb" => 1,
        "title" => "the red see",
        "Owner" => "sdeekden",
        "genre" => "Science",
        "year" => 2004,
        "pages" => 265
    ],
    [
        "numb" => 2,
        "title" => "informashens",
        "Owner" => "ahmad same",
        "genre" => "Technology",
        "year" => 2010,
        "pages" => 100
    ],
    [
        "numb" => 3,
        "title" => "lamersin",
        "Owner" => "mahmmod dader",
        "genre" => "Fiction",
        "year" => 1937,
        "pages" => 310
    ]
];

$genres = ["Fiction", "Non-Fiction", "Science", "History", "Biography", "Technology"];

$errors = [];
$data = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $owner = trim($_POST["Owner"]);
    $genre = $_POST["genre"];
    $year = $_POST["year"];
    $pages = $_POST["pages"];

    $data = $_POST;

    if ($title == "") {
        $errors["title"] = "title required";
    }

    if ($owner == "") {
        $errors["Owner"] = "owner required";
    }

    if ($genre == "") {
        $errors["genre"] = "genre required";
    }

    $currentYear = date("Y");

    if ($year == "" || $year < 1000 || $year > $currentYear) {
        $errors["year"] = "wrong year";
    }

    if ($pages == "" || $pages <= 0) {
        $errors["pages"] = "wrong pages";
    }

    if (empty($errors)) {

        $new_id = 0;

        foreach ($books as $b) {
            if ($b["numb"] > $new_id) {
                $new_id = $b["numb"];
            }
        }

        $new_id++;

        $books[] = [
            "numb" => $new_id,
            "title" => $title,
            "Owner" => $owner,
            "genre" => $genre,
            "year" => $year,
            "pages" => $pages
        ];

        $_SESSION["books"] = $books;
        $_SESSION["success"] = "Book added";

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Books Archive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">
    <div class="row">

        <div class="col-md-4">

            <h3>Add Book</h3>

            <?php if (isset($_SESSION["success"])) { ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION["success"]; unset($_SESSION["success"]); ?>
                </div>
            <?php } ?>

            <form method="POST">

                <input class="form-control mb-2" name="title" placeholder="Title">
                <?php if(isset($errors["title"])) echo $errors["title"]; ?>

                <input class="form-control mb-2" name="Owner" placeholder="Owner">
                <?php if(isset($errors["Owner"])) echo $errors["Owner"]; ?>

                <select class="form-control mb-2" name="genre">
                    <option value="">Select</option>
                    <?php foreach($genres as $g){ ?>
                        <option value="<?php echo $g; ?>"><?php echo $g; ?></option>
                    <?php } ?>
                </select>

                <?php if(isset($errors["genre"])) echo $errors["genre"]; ?>

                <input class="form-control mb-2" name="year" placeholder="Year">

                <?php if(isset($errors["year"])) echo $errors["year"]; ?>

                <input class="form-control mb-2" name="pages" placeholder="Pages">

                <?php if(isset($errors["pages"])) echo $errors["pages"]; ?>

                <button class="btn btn-primary w-100">Add</button>

            </form>

        </div>

        <div class="col-md-8">

            <h3>Books</h3>

            <table class="table table-bordered">

                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Owner</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Pages</th>
                </tr>

                <?php foreach($books as $b){ ?>
                    <tr>
                        <td><?= $b["numb"] ?></td>
                        <td><?= $b["title"] ?></td>
                        <td><?= $b["Owner"] ?></td>
                        <td><?= $b["genre"] ?></td>
                        <td><?= $b["year"] ?></td>
                        <td><?= $b["pages"] ?></td>
                    </tr>
                <?php } ?>

            </table>

        </div>

    </div>
</div>

</body>
</html>
