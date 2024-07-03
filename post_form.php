<?php require_once('funcs.php'); ?>

<!-- Main[Start] -->
<div class="min-h-screen w-5/6 flex flex-col flex-1 items-center bg-[#F1F6F5] rounded-lg">

  <!-- ShowSearchButton -->
  <button id="showSearchButton" class="fixed top-6 right-4 bg-[#7895B2] hover:bg-[#AAC4FF] text-white hover:text-slate-700 py-2 px-4 rounded-full shadow-md">
    <i class="fas fa-search"></i>
  </button>

  <!-- Posting area[Start] -->
  <form method="POST" action="" enctype="multipart/form-data" id="myForm" class="w-full flex flex-col justify-center items-center m-2">
    <div class="w-full flex flex-col justify-center m-2">
      <div class="p-4">
        <label for="name" class="text-sm sm:text-base md:text-lg lg:text-xl">名前：</label>
        <input type="text" name="name" id="name" placeholder="テストちゃん" class="w-full h-11 p-2 border rounded-md">
      </div>
      <div class="p-4">
        <label for="message" class="text-sm sm:text-base md:text-lg lg:text-xl">内容：</label>
        <textArea name="message" id="message" placeholder="140字以内で内容を入力してください。" rows="4" cols="40" class="w-full p-2 border rounded-md"></textArea>
        <div id="messageError" class="text-red-500 text-lg mt-1 hidden">内容は140文字以内で入力してください</div>
      </div>
      <div class="pb-4 px-4">
        <label for="picture" class="text-sm sm:text-base md:text-lg lg:text-xl">写真：</label>
        <div class="flex flex-col sm:flex-row justify-center items-center">
          <input type="file" name="picture" id="picture" accept="image/*" onchange="previewFile()" class="w-full h-11 py-2 my-2">
          <!-- accept="image/*" 画像ファイルのみを許可 -->
          <button type="submit" class="w-1/3 sm:w-1/4  border-2 rounded-md border-[#93CCCA] md:border md:border-slate-200 md:bg-transparent md:hover:bg-[#93CCCA] p-2 my-2"><i class="fas fa-paper-plane"></i></button>
        </div>
      </div>
      <!-- Photo Preview Area -->
      <div class="flex justify-center">
        <img src="" id="preview" class="hidden max-w-100% max-h-[300px]" alt="選択した画像のプレビュー">
      </div>
    </div>
  </form>
  <!-- Posting area[End] -->

  <!-- Search area[Start] -->
  <form method="GET" action="" id="searchForm" class="w-full flex flex-col sm:flex-row items-center p-4  hidden">
  <div class="w-full sm:w-3/4  py-auto sm:ml-2">
    <label for="search" class="text-sm sm:text-base md:text-lg lg:text-xl">内容検索:</label>
    <input type="text" name="search" placeholder="キーワードで内容を検索" class="w-full h-11 p-2 border rounded-md sm:rounded-r-none" id="search" value="<?= h(isset($_GET['search']) ? $_GET['search'] : '') ?>">
  </div>
  <div class="w-1/2 sm:w-1/4 flex justify-around items-end sm:items-stretch mt-2 sm:pt-6 sm:mt-0 sm:mr-2">
    <button type="submit" id="searchButton" class="w-1/3 sm:w-1/2 border-2 rounded-md border-[#FAEAB1] sm:rounded-none md:border md:border-slate-200 md:bg-transparent md:hover:bg-[#FAEAB1] p-2 m-2 sm:mx-0 md:mt-3 md:py-2">
      <i class="fas fa-search"></i>
    </button>
    <button type="button" class="w-1/3 sm:w-1/2 border-2 rounded-md border-[#D1D1D1] sm:rounded-l-none md:border md:border-slate-200 md:bg-transparent md:hover:bg-[#D1D1D1] p-2 m-2 sm:mx-0 md:mt-3" onclick="clearSearch()">
      <i class="fas fa-times-circle"></i>
    </button>
  </div>
</form>
  <!-- Search area[End] -->

  <!-- Display area[Start] -->
  <div class="w-full m-4 border-t flex flex-col items-center">
    <h2 class="text-md sm:text-lg md:text-xl lg:text-2xl text-center my-4 font-mochiy-pop-one">Posts</h2>
    <!-- SortButton -->
    <div class="w-1/2 flex justify-around mb-4">
      <button type="button" name="order" id="ascButton" value="asc" class="w-1/3 sm:w-1/4 border-2 rounded-md border-[#FFC4C4] md:border md:border-slate-200 md:bg-transparent md:hover:bg-[#FFC4C4] p-2 m-2">
        <i class="fas fa-sort-amount-up"></i>
      </button>
      <button type="button" name="order" id="descButton" value="desc" class="w-1/3 sm:w-1/4 border-2 rounded-md border-[#AAC4FF] md:border md:border-slate-200 md:bg-transparent md:hover:bg-[#AAC4FF] p-2 m-2">
        <i class="fas fa-sort-amount-down"></i>
      </button>
    </div>
    <!-- Posts[start] -->
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
      <!-- 以下に投稿内容が表示される -->