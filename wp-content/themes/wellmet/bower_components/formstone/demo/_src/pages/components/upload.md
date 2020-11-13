{"template":"component.html","title":"Upload","demo":"<h4>Basic</h4>\r\n\r\n<!-- START: FIRSTDEMO -->\r\n\r\n<style>\r\n  .filelists { margin: 20px 0; }\r\n  .filelists h5 { margin: 10px 0 0; }\n  .filelists .start_all { background: #455a64; border-radius: 2px; color: #fff; cursor: pointer; clear: both; display: inline-block; font-size: 10px; margin: 0 10px 0 0; padding: 8px 12px; text-transform: uppercase; }\r\n  .filelists .cancel_all { color: red; cursor: pointer; clear: both; display: inline-block; font-size: 10px; margin: 0; text-transform: uppercase; }\r\n  .filelist { margin: 0; padding: 10px 0; }\r\n  .filelist li { background: #fff; border-bottom: 1px solid #ECEFF1; font-size: 14px; list-style: none; padding: 5px; position: relative; }\n  .filelist li:before { display: none !important; } /* main site demos */\n  .filelist li .bar { background: #eceff1; content: ''; height: 100%; left: 0; position: absolute; top: 0; width: 0; z-index: 0;\r\n    -webkit-transition: width 0.1s linear;\r\n        transition: width 0.1s linear;\r\n  }\r\n  .filelist li .content { display: block; overflow: hidden; position: relative; z-index: 1; }\r\n  .filelist li .file { color: #455A64; float: left; display: block; overflow: hidden; text-overflow: ellipsis; max-width: 50%; white-space: nowrap; }\r\n  .filelist li .progress { color: #B0BEC5; display: block; float: right; font-size: 10px; text-transform: uppercase; }\r\n  .filelist li .cancel { color: red; cursor: pointer; display: block; float: right; font-size: 10px; margin: 0 0 0 10px; text-transform: uppercase; }\n  /* .filelist.started li .cancel { display: block; } */\n  /* .filelist li .remove { color: red; cursor: pointer; display: block; float: right; font-size: 10px; margin: 0 0 0 10px; text-transform: uppercase; }\n  .filelist.started li .remove { display: none; } */\r\n  .filelist li.error .file { color: red; }\r\n  .filelist li.error .progress { color: red; }\r\n  .filelist li.error .cancel { display: none; }\r\n</style>\r\n\r\n<script>\r\n  Formstone.Ready(function() {\r\n    $(\".upload\").upload({\r\n      maxSize: 1073741824,\r\n      beforeSend: onBeforeSend\r\n    }).on(\"start.upload\", onStart)\r\n      .on(\"complete.upload\", onComplete)\r\n      .on(\"filestart.upload\", onFileStart)\r\n      .on(\"fileprogress.upload\", onFileProgress)\r\n      .on(\"filecomplete.upload\", onFileComplete)\r\n      .on(\"fileerror.upload\", onFileError)\n      // .on(\"fileremove.upload\", onFileRemove)\r\n      .on(\"chunkstart.upload\", onChunkStart)\r\n      .on(\"chunkprogress.upload\", onChunkProgress)\r\n      .on(\"chunkcomplete.upload\", onChunkComplete)\r\n      .on(\"chunkerror.upload\", onChunkError)\r\n      .on(\"queued.upload\", onQueued);\r\n\r\n    $(\".filelist.queue\").on(\"click\", \".cancel\", onCancel);\n    // $(\".filelist.queue\").on(\"click\", \".remove\", onRemove);\r\n    $(\".cancel_all\").on(\"click\", onCancelAll);\n    $(\".start_all\").on(\"click\", onStart);\r\n  });\r\n\r\n  function onCancel(e) {\r\n    console.log(\"Cancel\");\r\n    var index = $(this).parents(\"li\").data(\"index\");\r\n    $(this).parents(\"form\").find(\".upload\").upload(\"abort\", parseInt(index, 10));\r\n  }\r\n\r\n  function onCancelAll(e) {\r\n    console.log(\"Cancel All\");\r\n    $(this).parents(\"form\").find(\".upload\").upload(\"abort\");\r\n  }\n\n  // function onRemove(e) {\n  //   console.log(\"Remove\");\n  //   var index = $(this).parents(\"li\").data(\"index\");\n  //   $(this).parents(\"form\").find(\".upload\").upload(\"remove\", parseInt(index, 10));\n  // }\r\n\r\n  function onBeforeSend(formData, file) {\r\n    console.log(\"Before Send\");\r\n    formData.append(\"test_field\", \"test_value\");\r\n    // return (file.name.indexOf(\".jpg\") < -1) ? false : formData; // cancel all jpgs\r\n    return formData;\r\n  }\r\n\r\n  function onQueued(e, files) {\r\n    console.log(\"Queued\");\r\n    var html = '';\r\n    for (var i = 0; i < files.length; i++) {\r\n      // html += '<li data-index=\"' + files[i].index + '\"><span class=\"content\"><span class=\"file\">' + files[i].name + '</span><span class=\"remove\">Remove</span><span class=\"cancel\">Cancel</span><span class=\"progress\">Queued</span></span><span class=\"bar\"></span></li>';\n      html += '<li data-index=\"' + files[i].index + '\"><span class=\"content\"><span class=\"file\">' + files[i].name + '</span><span class=\"cancel\">Cancel</span><span class=\"progress\">Queued</span></span><span class=\"bar\"></span></li>';\r\n    }\r\n\r\n    $(this).parents(\"form\").find(\".filelist.queue\")\r\n      .append(html);\r\n  }\r\n\r\n  function onStart(e, files) {\r\n    console.log(\"Start\");\r\n    $(this).parents(\"form\").find(\".filelist.queue\")\n      .addClass(\"started\")\r\n      .find(\"li\")\r\n      .find(\".progress\").text(\"Waiting\");\r\n  }\r\n\r\n  function onComplete(e) {\r\n    console.log(\"Complete\");\r\n    // All done!\r\n  }\r\n\r\n  function onFileStart(e, file) {\r\n    console.log(\"File Start\");\r\n    $(this).parents(\"form\").find(\".filelist.queue\")\r\n      .find(\"li[data-index=\" + file.index + \"]\")\r\n      .find(\".progress\").text(\"0%\");\r\n  }\r\n\r\n  function onFileProgress(e, file, percent) {\r\n    console.log(\"File Progress\");\r\n    var $file = $(this).parents(\"form\").find(\".filelist.queue\").find(\"li[data-index=\" + file.index + \"]\");\r\n\r\n    $file.find(\".progress\").text(percent + \"%\")\r\n    $file.find(\".bar\").css(\"width\", percent + \"%\");\r\n  }\r\n\r\n  function onFileComplete(e, file, response) {\r\n    console.log(\"File Complete\");\r\n    if (response.trim() === \"\" || response.toLowerCase().indexOf(\"error\") > -1) {\r\n      $(this).parents(\"form\").find(\".filelist.queue\")\r\n        .find(\"li[data-index=\" + file.index + \"]\").addClass(\"error\")\r\n        .find(\".progress\").text(response.trim());\r\n    } else {\r\n      var $target = $(this).parents(\"form\").find(\".filelist.queue\").find(\"li[data-index=\" + file.index + \"]\");\r\n      $target.find(\".file\").text(file.name);\r\n      $target.find(\".progress\").remove();\r\n      $target.find(\".cancel\").remove();\r\n      $target.appendTo( $(this).parents(\"form\").find(\".filelist.complete\") );\r\n    }\r\n  }\r\n\r\n  function onFileError(e, file, error) {\r\n    console.log(\"File Error\");\r\n    $(this).parents(\"form\").find(\".filelist.queue\")\r\n      .find(\"li[data-index=\" + file.index + \"]\").addClass(\"error\")\r\n      .find(\".progress\").text(\"Error: \" + error);\r\n  }\n\n  function onFileRemove(e, file, error) {\n    console.log(\"File Removed\");\n    $(this).parents(\"form\").find(\".filelist.queue\")\n      .find(\"li[data-index=\" + file.index + \"]\").addClass(\"error\")\n      .find(\".progress\").text(\"Removed\");\n  }\r\n\r\n  function onChunkStart(e, file) {\r\n    console.log(\"Chunk Start\");\r\n  }\r\n\r\n  function onChunkProgress(e, file, percent) {\r\n    console.log(\"Chunk Progress\");\r\n  }\r\n\r\n  function onChunkComplete(e, file, response) {\r\n    console.log(\"Chunk Complete\");\r\n  }\r\n\r\n  function onChunkError(e, file, error) {\r\n    console.log(\"Chunk Error\");\r\n  }\n\n  function onStart(e) {\n    console.log(\"Start Upload\");\n    $(this).parents(\"form\").find(\".upload\").upload(\"start\");\n  }\r\n</script>\r\n\r\n<div class=\"demo_container\">\r\n  <div class=\"demo_example\">\r\n    <form action=\"#\" method=\"GET\" class=\"form demo_form\">\r\n      <div class=\"upload\" data-upload-options='{\"action\":\"../_extra/upload-target.php\"}'></div>\n      <div class=\"filelists\">\r\n        <h5>Complete</h5>\r\n        <ol class=\"filelist complete\">\r\n        </ol>\r\n        <h5>Queued</h5>\r\n        <ol class=\"filelist queue\">\r\n        </ol>\r\n        <span class=\"cancel_all\">Cancel All</span>\r\n      </div>\r\n    </form>\r\n  </div>\r\n  <div class=\"demo_code\">\r\n    <pre><code class=\"language-html\">&lt;div class=&quot;upload&quot;&gt;&lt;/div&gt;</code></pre>\r\n    <pre><code class=\"language-javascript\">$(\".upload\").upload({\r\n  action: \"//example.com/handle-upload.php\"\r\n});</code></pre>\r\n  </div>\r\n</div>\r\n\r\n<!-- END: FIRSTDEMO -->\r\n\r\n<h4>Chunked Uploads</h4>\r\n<div class=\"demo_container\">\r\n  <div class=\"demo_example\">\r\n    <form action=\"#\" method=\"GET\" class=\"form demo_form\">\r\n      <div class=\"upload\" data-upload-options='{\"action\":\"../_extra/upload-chunked.php\",\"chunked\":true}'></div>\n      <div class=\"filelists\">\r\n        <h5>Complete</h5>\r\n        <ol class=\"filelist complete\">\r\n        </ol>\r\n        <h5>Queued</h5>\r\n        <ol class=\"filelist queue\">\r\n        </ol>\r\n        <span class=\"cancel_all\">Cancel All</span>\r\n      </div>\r\n    </form>\r\n  </div>\r\n  <div class=\"demo_code\">\r\n    <pre><code class=\"language-html\">&lt;div class=&quot;upload&quot;&gt;&lt;/div&gt;</code></pre>\r\n    <pre><code class=\"language-javascript\">$(\".upload\").upload({\r\n  action: \"//example.com/handle-chunked-upload.php\",\r\n  chunked: true\r\n});</code></pre>\r\n  </div>\r\n</div>\n\n<h4>Manual Upload</h4>\n<div class=\"demo_container\">\n  <div class=\"demo_example\">\n    <form action=\"#\" method=\"GET\" class=\"form demo_form\">\n      <div class=\"upload\" data-upload-options='{\"action\":\"../_extra/upload-target.php\",\"autoUpload\":false}'></div>\n      <div class=\"filelists\">\n        <h5>Complete</h5>\n        <ol class=\"filelist complete\">\n        </ol>\n        <h5>Queued</h5>\n        <ol class=\"filelist queue\">\n        </ol>\n        <span class=\"start_all\">Start Upload</span>\n        <span class=\"cancel_all\">Cancel All</span>\n      </div>\n    </form>\n  </div>\n  <div class=\"demo_code\">\n    <pre><code class=\"language-html\">&lt;div class=&quot;upload&quot;&gt;&lt;/div&gt;</code></pre>\n    <pre><code class=\"language-javascript\">$(\".upload\").upload({\n  action: \"//example.com/handle-chunked-upload.php\",\n  autoUpload: false\n});</code></pre>\n  </div>\n</div>\r\n\r\n<h4>No Theme</h4>\r\n<div class=\"demo_container\">\r\n  <div class=\"demo_example\">\r\n    <form action=\"#\" method=\"GET\" class=\"form demo_form\">\r\n      <div class=\"upload\" data-upload-options='{\"action\":\"../_extra/upload-target.php\",\"theme\":\"\"}'></div>\r\n      <div class=\"filelists\">\r\n        <h5>Complete</h5>\r\n        <ol class=\"filelist complete\">\r\n        </ol>\r\n        <h5>Queued</h5>\r\n        <ol class=\"filelist queue\">\r\n        </ol>\r\n        <span class=\"cancel_all\">Cancel All</span>\r\n      </div>\r\n    </form>\r\n  </div>\r\n  <div class=\"demo_code\">\r\n    <pre><code class=\"language-html\">&lt;div class=&quot;upload&quot;&gt;&lt;/div&gt;</code></pre>\r\n    <pre><code class=\"language-javascript\">$(\".upload\").upload({\r\n  action: \"//example.com/handle-upload.php\",\r\n  theme: \"\"\r\n});</code></pre>\r\n  </div>\r\n</div>\r\n","asset_root":"../","year":2020}

 #Upload Demo
<p class="back_link"><a href="https://formstone.it/components/upload">View Documentation</a></p>