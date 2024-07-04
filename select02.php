<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>おさかなハぅマっチ？</title>
    <link rel="stylesheet" href="css/style03.css?<?php echo date('YmdHis');?>"/>
    <link rel="icon" href="img/Logo2.webp" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="header">
        <img id="logo" src="img/Logo2.webp" alt="">
        <h1>おさかなハぅマっチ？</h1>
    </div>
    <main>
        <div id="button02">
            <select id="fish-select">
                <option value="">魚を選択して下さい</option>
                <option value="all">全データ表示</option>
                <option value="ハマチ">ハマチ</option>
                <option value="マグロ">マグロ</option>
                <option value="サバ">サバ</option>
                <option value="アジ">アジ</option>
            </select>
            <button id="back" onclick="location.href='main01.php'">戻る</button>
        </div>
        <canvas id="priceChart"></canvas>
    </main>
    <footer>
        © 2024 Satoru Tauchi
    </footer>

    <!-- イベントリスナーChangeでデータを呼び出し -->
    <script src="js/jquery-2.1.3.min.js"></script>
    <script>
        document.getElementById('fish-select').addEventListener('change', function() {
            const selectedFish = this.value;
            if (selectedFish !== "") {
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'GET',
                    data: { fish: selectedFish === "all" ? "" : selectedFish },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // 日付でソート
                            data.sort((a, b) => new Date(a.date) - new Date(b.date));

                            let dates = [];
                            let prices = [];

                            data.forEach(function(item) {
                                dates.push(item.date);
                                prices.push(item.price);
                            });

                            drawChart(dates, prices);
                        }
                    },
                    error: function() {
                        alert('データの取得に失敗しました。');
                    }
                });
            } else {
                alert('魚を選択してください');
            }
        });

        let chart;

        function drawChart(dates, prices) {
            const ctx = document.getElementById('priceChart').getContext('2d');

            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: '価格 (円/kg)',
                        data: prices,
                        borderColor: 'rgba(0, 0, 255, 0.3)', // 折れ線の色を青に設定
                        backgroundColor: 'rgba(0, 0, 255, 0.1)', // 領域を透明度の高い青で塗る
                        borderWidth: 3, // 折れ線の太さを設定
                        fill: true // 領域を塗る
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: '日付'
                            },
                            grid: {
                                borderWidth: 2 // X軸の罫線を太く
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '価格 (円/kg)'
                            },
                            grid: {
                                borderWidth: 2 // Y軸の罫線を太く
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
