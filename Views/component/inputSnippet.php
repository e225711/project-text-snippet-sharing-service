<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 mb-3">
      <label for="editor-container" class="form-label">Editor</label>
      <div id="editor-container" style="height: 300px;border:1px solid grey"></div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" id="title">
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 mb-3">
      <label for="language-select" class="form-label">Select Language</label>
      <select class="form-select" id="language-select">
        <option value="plaintext">Plain Text</option>
        <option value="python">Python</option>
        <option value="java">Java</option>
        <!-- Add more options for other languages if needed -->
      </select>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 mb-3">
      <label for="expiry-select" class="form-label">Set Expiration</label>
      <select class="form-select" id="expiry-select">
        <option value="1 minute">1 minute</option>
        <option value="10 minutes">10 minutes</option>
        <option value="1 hour">1 hour</option>
        <option value="1 day">1 day</option>
        <option value="Permanent">Permanent</option>
      </select>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
    </div>
  </div>
</div>

<!-- Monaco Editor Loader -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs/loader.min.js"></script>
<script>
  var editor; // Editor variable defined in the global scope

  // Load Monaco Editor
  require.config({
    paths: {
      'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs'
    }
  });
  require(['vs/editor/editor.main'], function() {
    // Initialize editor
    editor = monaco.editor.create(document.getElementById('editor-container'), {
      value: '',
      language: 'plaintext' // Default language
    });
  });

  document.getElementById('submit-btn').addEventListener('click', function() {
    var title = document.getElementById('title').value;
    var language = document.getElementById('language-select').value;
    var expiry = document.getElementById('expiry-select').value;
    var content = editor.getValue();

    console.log("Title:", title);
    console.log("Language:", language);
    console.log("Expiry:", expiry);
    console.log("Content:", content);

    var xhr = new XMLHttpRequest();
    var url = ""; // insertSnippet.php のパスに変更
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var data = "title=" + encodeURIComponent(title) +
      "&language=" + encodeURIComponent(language) +
      "&expiry=" + encodeURIComponent(expiry) +
      "&content=" + encodeURIComponent(content);

    xhr.send(data);

    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log("Snippet insertion successful");
          // JSONレスポンスを待機
          var jsonResponse = JSON.parse(xhr.responseText);
          console.log("Response:", jsonResponse);
          // URL にリダイレクト
          window.location.href = jsonResponse.snippetLink;
        } else {
          console.error("Error:", xhr.statusText);
        }
      }
    };
  });
</script>
