<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafiks</title>
    <script src="https://kurvar.lv/eksamens/"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 100px;
        }

        #search-bar-container {
            display: flex;
            align-items: center;
        }

        #search-bar {
            padding: 5px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        .header-button {
            padding: 8px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #333;
            color: #fff;
            transition: color 0.3s;
        }

        .header-button:hover {
            color: yellow;
        }

        #cards-container {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-wrap: wrap;
        }

        .card {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 30px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            height: 400px;
        }

        .card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            max-height: 200px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div id="search-bar-container">
            <input type="text" id="search-bar" placeholder="Search...">
        </div>
        <button class="header-button" onclick="app.filterByCategory('Functional')">Functional</button>
        <button class="header-button" onclick="app.filterByCategory('Object-Oriented')">Object-Oriented</button>
        <button class="header-button" onclick="app.filterByCategory('Procedural')">Procedural</button>
        <button class="header-button" onclick="app.filterByCategory('Scripting')">Scripting</button>
    </header>

    <?php
        // API piekļuves punkts
        $api_url = "https://kurvar.lv/eksamens/";

        // Veic GET pieprasījumu
        $response = file_get_contents($api_url);

        // Pārbauda, vai pieprasījums bija veiksmīgs
        if ($response !== false) {
            // Atbildes JSON apstrāde
            $data = json_decode($response, true);
            // Ielādē JSON datus JavaScript kodā
            echo "<script>";
            echo "const jsonData = " . json_encode($data) . ";";
            echo "</script>";
        } else {
            echo "<p>Neizdevās veikt pieprasījumu.</p>";
        }
    ?>

    <div id="cards-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const app = new App(jsonData);
            app.initialize();
            app.addSearchValidation();
        });

        class App {
            constructor(data) {
                this.data = data;
                this.cardsContainer = document.getElementById('cards-container');
                this.searchBar = document.getElementById('search-bar');
            }

            filterByCategory(category) {
                const uniqueIds = new Set();
                const filteredData = this.data.filter((item) => {
                    if (item.type === category && !uniqueIds.has(item.id)) {
                        uniqueIds.add(item.id);
                        return true;
                    }
                    return false;
                });
                this.renderCards(filteredData);
            }

            initialize() {
                this.renderCards(this.data);
            }

            addSearchValidation() {
                this.searchBar.addEventListener('input', () => {
                    this.validateSearchInput();
                });
            }

            validateSearchInput() {
                const inputValue = this.searchBar.value;
                const sanitizedValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
                this.searchBar.value = sanitizedValue;
                this.filterCardsBySearch(sanitizedValue);
            }

            filterCardsBySearch(searchValue) {
                const filteredData = this.data.filter(item =>
                    item.title.toLowerCase().includes(searchValue.toLowerCase()) ||
                    item.type.toLowerCase().includes(searchValue.toLowerCase()) ||
                    item.description.toLowerCase().includes(searchValue.toLowerCase())
                );
                this.renderCards(filteredData);
            }

            renderCards(filteredData) {
                this.cardsContainer.innerHTML = '';
                filteredData.forEach(item => {
                    const cardElement = document.createElement('div');
                    cardElement.className = 'card';
                    cardElement.innerHTML = `
                        <img src='${item.image}' alt='Image'>
                        <h3>${item.title}</h3>
                        <p>${item.description}</p>
                        <p>${item.type}</p>
                        <p>ID: ${item.id}</p>
                    `;
                    this.cardsContainer.appendChild(cardElement);
                });
            }
        }
    </script>
</body>
</html>
