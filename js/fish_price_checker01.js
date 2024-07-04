// Firebaseの設定と初期化
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.0/firebase-app.js";
import { getDatabase, ref, push, set, onValue, remove } from "https://www.gstatic.com/firebasejs/9.1.0/firebase-database.js";

// Firebaseプロジェクトの設定情報
const firebaseConfig = {
  apiKey: "ABvtSb9Y",
  authDomain: "kadai05-d0a57.firebaseapp.com",
  projectId: "kadai05-d0a57",
  storageBucket: "kadai05-d0a57.appspot.com",
  messagingSenderId: "131492232930",
  appId: "1:131492232930:web:b20d27dfe34e28072b68bb"
};

// Firebaseアプリの初期化
const app = initializeApp(firebaseConfig);

// データベースの参照を取得
const db = getDatabase(app);
const dbRef = ref(db, "fish_price");
const database = getDatabase(app);

// 1.Saveクリックイベント
function saveData() {
  const dateValue = $("#date").val();
  const fishValue = $("#fish").val();
  const placeValue = $("#place").val();
  const priceValue = $("#price").val();
  const remarksValue = $("#remarks").val();
  const fileInput = document.getElementById("imgFile");

  if (fileInput.files.length === 0) {
      alert("画像ファイルを選択してください。");
      return;
  }

  const file = fileInput.files[0];
  const reader = new FileReader();

  reader.onload = (event) => {
      const fileData = event.target.result;

      // 日付の参照を設定
      const dateRef = ref(db, `fish_price/${fishValue}/${dateValue}`);

      // 保存するデータオブジェクト
      const data = {
        date: dateValue,
        fish: fishValue,
        place: placeValue,
        price: priceValue,
        remarks: remarksValue,
        fileInput: fileData // 画像データはDataURLとして保存
      };

      set(dateRef, data)
      .then(() => {
          console.log('Data saved successfully!');
          clearForm();
      })
      .catch(error => {
          console.error('Error saving data:', error);
      });
  };

  reader.readAsDataURL(file);
}


// Saveクリック後に入力内容をクリアする
function clearForm() {
    $("#date").val("");
    $("#fish").val("");
    $("#place").val("");
    $("#price").val("");
    $("#remarks").val("");
    $("#imgFile").val("");
    $(".preview").css("background-image", "none"); // プレビュー画像をクリア
    window.myLine.destroy();// グラフエリアをクリア
}

$("#save").on("click", saveData);

export { saveData, clearForm };


// ファイル選択欄の変更イベントに関数を結び付けて、プレビュー表示を行う
$('#imgFile').change(
  function () {
      if (!this.files.length) {
          return;
      }

      var file = $(this).prop('files')[0];
      var fr = new FileReader();
      $('.preview').css('background-image', 'none');
      fr.onload = function() {
          $('.preview').css('background-image', 'url(' + fr.result + ')');
      }
      fr.readAsDataURL(file);
  }
);

//2.クリアをクリックした際に入力内容をリセットする
$("#empty").on("click", function () {
  $("#date").val("");
  $("#fish").val(""); 
  $("#place").val("");
  $("#price").val(""); 
  $("#remarks").val(""); 
  $("#imgFile").val("");
  $(".preview").css("background-image", "none"); 
  $("#list").empty();
  window.myLine.destroy();// グラフエリアをクリア 
});

//3.プライスチェック クリックイベント
$("#check").on("click", function () {
  // 選択された魚
  var selectedFish = $("#fish").val();
  // 選択された魚のデータセットを取得
  var fishDataset = fishData[selectedFish];
  if (!fishDataset) {
      alert("魚を選択してください。");
      return;
  }
  
  // 入力された価格
  var priceValue = $("#price").val();
  if (!priceValue) {
      alert("価格を入力してください。");
      return;
  }
  
  // 選択された魚のデータセットと価格を比較して、結果を表示
  var message = "";
  fishDataset.forEach(function(dataset) {
      if (priceValue < dataset.data[dataset.data.length - 1]) {
          message += dataset.label + "と比べて買い！\n";
      } else {
          message += dataset.label + "と比べてうーん…\n";
      }
  });

  
  // 結果をアラートで表示
  alert(message);
});

//4.データベース表示 クリックイベント
  
// データベースからデータを取得してHTMLに表示する関数
function fetchData() {
  onValue(dbRef, (snapshot) => {
    const data = snapshot.val();
    if (data) {
        displayData(data);
    } else {
        console.log("No data available");
    }
}, (error) => {
    console.error("Error fetching data:", error);
});
}

// 取得したデータをHTMLに表示する関数

function displayData(data) {
  const list = $("#list");
  list.empty(); // 既存のリストをクリア

  // データが `fish` キー直下にあると仮定し、その内部のデータをループ処理
  for (const dateKey in data) {
    if (data.hasOwnProperty(dateKey)) {
      const dayData = data[dateKey];  // 日付キーでデータを取得
      for (const key in dayData) {
        const item = dayData[key];
        if (!item) {
          console.error('Missing data for key:', key);
          continue;  // データが不完全な場合はスキップ
        }
        const html = `
            <div class="grid-item">
                <img src="${item.fileInput}" alt="画像">
                <p>日付: ${item.date}<br>おさかな: ${item.fish}<br>産地: ${item.place}<br>金額: ${item.price} 円/kg<br>備考: ${item.remarks}</p>
            </div>
        `;
        list.append(html);
      }
    }
  }
}

// データを見るボタンクリック時にデータをフェッチ
$("#database").on("click", fetchData);

// 5.削除ボタンクリック時にfirebase databaseを削除する
document.getElementById('clear').addEventListener('click', function() {
  const dbRef = ref(database, 'fish_price');
  remove(dbRef)
      .then(() => {
          console.log('Data removed successfully!');
          alert('データが正常に削除されました。');
      })
      .catch((error) => {
          console.error('Failed to remove data', error);
          alert('データ削除に失敗しました。');
      });
});


// document.getElementById('fishPriceForm').addEventListener('submit', function(e) {
//   e.preventDefault();
//   // ここでフォームデータの送信処理を行う
//   // 成功後、以下のトースト表示とフォームリセットを行う
//   document.getElementById('toast').classList.add('show');
//   setTimeout(() => {
//     document.getElementById('toast').classList.remove('show');
//   }, 3000);
//   this.reset();
// });

// document.querySelector('.file-input-wrapper input[type=file]').addEventListener('change', function() {
//   if (this.files && this.files[0]) {
//     var fileName = this.files[0].name;
//     this.parentElement.querySelector('.btn').textContent = fileName;
//   }
// });
