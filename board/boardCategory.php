<aside class="board__filter">
    <div class="filter__type">
        <div class="type__check">
            <label for="filter-toggle1" class="checkbox">
                <input type="checkbox" id="filter-toggle1"
                    onClick="handleCheckboxClick('filter-toggle1', 'link-toggle1')">
                <span class="on"></span>
                <a id="link-toggle1" href="boardCate.php">전체 글</a>
            </label>

            <label for="filter-toggle2" class="checkbox">
                <input type="checkbox" id="filter-toggle2"
                    onClick="handleCheckboxClick('filter-toggle2', 'link-toggle2')">
                <span class="on"></span>
                <a id="link-toggle2" href="boardCate.php?category=공지">공지</a>
            </label>

            <label for="filter-toggle3" class="checkbox">
                <input type="checkbox" id="filter-toggle3"
                    onClick="handleCheckboxClick('filter-toggle3', 'link-toggle3')">
                <span class="on"></span>
                <a id="link-toggle3" href="boardCate.php?category=질문">질문</a>
            </label>

            <label for="filter-toggle4" class="checkbox">
                <input type="checkbox" id="filter-toggle4"
                    onClick="handleCheckboxClick('filter-toggle4', 'link-toggle4')">
                <span class="on"></span>
                <a id="link-toggle4" href="boardCate.php?category=자유">자유</a>
            </label>
        </div>
        <div method="post" action="search.php">
            <legend class="blind">게시판 검색</legend>
            <input type="text" name="searchKeyword" id="searchKeyword" placeholder="검색어를 입력하세요" required>
        </div>
    </div>
</aside>

<div id="searchResults">
    <!-- 여기에 검색 결과가 표시될 것입니다. -->
</div>

<script>
    // 페이지 로드 시 해당 카테고리 체크박스 활성화
    const category = '<?= $category ?>';
    if (category) {
        document.getElementById(`filter-toggle${category}`).checked = true;
    }



    function handleCheckboxClick(checkboxId, linkId, categoryName) {
        const link = document.getElementById(linkId);
        const checkbox = document.getElementById(checkboxId);

        if (checkbox.checked) {
            // 체크박스가 선택된 경우, 해당 링크를 클릭하고 다른 링크의 선택을 해제
            link.click();
        } else {
            // 체크박스가 해제된 경우, 해당 링크만 선택 해제
            link.classList.remove('selected');
        }
    }


    document.getElementById('searchKeyword').addEventListener('change', function (event) {
        event.preventDefault(); // 기본 폼 제출 동작 방지

        // 검색어 가져오기
        var searchKeyword = document.getElementById('searchKeyword').value

        // fetch를 이용한 비동기적 검색 요청
        fetch('search.php', {
            method: 'POST',
            body: new URLSearchParams({ searchKeyword })
        })
            .then(function (response) {
                return response.text();
            })
            .then(function (data) {
                // 검색 결과를 searchResults 요소에 추가
                document.getElementById('searchResults').innerHTML = data;
            })
            .catch(function (error) {
                console.log('검색 요청에 실패했습니다', error);
            });
    });
</script>