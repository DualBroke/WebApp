<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T1 - 선수 관리</title>
    <link rel="stylesheet" href="third.css">
    <style>
        .action-btns {
            display: flex;
            gap: 10px;
        }
        .btn-edit {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }
        .btn-delete {
            background: linear-gradient(135deg, #666, #444);
        }
        .search-section {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            align-items: center;
        }
        .search-section input,
        .search-section select {
            padding: 12px 18px;
            background: var(--t1-gray);
            border: 1px solid var(--t1-light-gray);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
        }
        .stats-box {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-item {
            background: var(--t1-dark);
            border: 1px solid var(--t1-gray);
            border-radius: 10px;
            padding: 20px 30px;
            text-align: center;
        }
        .stat-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--t1-red);
        }
        .stat-label {
            color: #888;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="t1-background"></div>
    
    <nav class="t1-navbar">
        <div class="nav-content">
            <a href="third_home.php" class="nav-logo">T1</a>
            <ul class="nav-menu">
                <li><a href="third_home.php">홈</a></li>
                <li><a href="third1.php" class="active">선수 관리</a></li>
                <li><a href="third2.php">선수 등록</a></li>
                <li><a href="third3.php">대회 기록</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="t1-container">
        <h1 class="section-title">PLAYER MANAGEMENT</h1>
        
        <?php
            $conn = mysqli_connect("localhost", "root", "");
            $totalPlayers = 0;
            $positions = [];
            
            if ($conn) {
                mysqli_select_db($conn, "t1_db");
                
                // 삭제 처리
                if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
                    $delNum = intval($_GET['delete']);
                    $qry = "DELETE FROM players WHERE playerNum = " . $delNum;
                    $rst = mysqli_query($conn, $qry);
                    if ($rst) {
                        echo "<script>alert('선수가 삭제되었습니다.'); location.href='third1.php';</script>";
                    }
                }
                
                // 통계 조회
                $qry = "SELECT COUNT(*) as total FROM players";
                $rst = mysqli_query($conn, $qry);
                if ($rst) {
                    $row = mysqli_fetch_assoc($rst);
                    $totalPlayers = $row['total'];
                }
                
                $qry = "SELECT sPosition, COUNT(*) as cnt FROM players GROUP BY sPosition";
                $rst = mysqli_query($conn, $qry);
                if ($rst) {
                    while ($row = mysqli_fetch_assoc($rst)) {
                        $positions[$row['sPosition']] = $row['cnt'];
                    }
                }
        ?>
        
        <!-- 통계 -->
        <div class="stats-box">
            <div class="stat-item">
                <div class="stat-number"><?= $totalPlayers ?></div>
                <div class="stat-label">전체 선수</div>
            </div>
            <?php foreach ($positions as $pos => $cnt) { ?>
            <div class="stat-item">
                <div class="stat-number"><?= $cnt ?></div>
                <div class="stat-label"><?= $pos ?></div>
            </div>
            <?php } ?>
        </div>
        
        <!-- 검색 및 필터 -->
        <div class="search-section">
            <input type="text" id="searchInput" placeholder="선수 검색..." onkeyup="filterTable()">
            <select id="positionFilter" onchange="filterTable()">
                <option value="">전체 포지션</option>
                <option value="탑">탑</option>
                <option value="정글">정글</option>
                <option value="미드">미드</option>
                <option value="원딜">원딜</option>
                <option value="서포터">서포터</option>
            </select>
            <a href="third2.php" class="t1-btn">+ 새 선수 등록</a>
        </div>
        
        <!-- 선수 테이블 -->
        <table class="t1-table" id="playersTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>닉네임</th>
                    <th>실명</th>
                    <th>포지션</th>
                    <th>생년월일</th>
                    <th>데뷔년도</th>
                    <th>시그니처</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $qry = "SELECT * FROM players ORDER BY FIELD(sPosition, '탑', '정글', '미드', '원딜', '서포터'), playerNum";
                $rst = mysqli_query($conn, $qry);
                $nRows = mysqli_num_rows($rst);
                
                if ($nRows > 0) {
                    $idx = 1;
                    while ($player = mysqli_fetch_assoc($rst)) {
            ?>
                <tr data-position="<?= $player['sPosition'] ?>">
                    <td><?= $idx++ ?></td>
                    <td style="color: var(--t1-red); font-weight: 700;"><?= $player['sNickname'] ?></td>
                    <td><?= $player['sRealName'] ?></td>
                    <td><span class="player-position"><?= $player['sPosition'] ?></span></td>
                    <td><?= $player['sBirthDate'] ?></td>
                    <td><?= $player['iDebutYear'] ?>년</td>
                    <td style="color: #888; font-style: italic;"><?= $player['sSignature'] ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="third2.php?playerNum=<?= $player['playerNum'] ?>" class="t1-btn t1-btn-small btn-edit">편집</a>
                            <button class="t1-btn t1-btn-small btn-delete" onclick="deletePlayer(<?= $player['playerNum'] ?>, '<?= $player['sNickname'] ?>')">삭제</button>
                        </div>
                    </td>
                </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center; color: #888; padding: 40px;'>등록된 선수가 없습니다. <a href='third2.php' style='color: var(--t1-red);'>선수를 등록해주세요.</a></td></tr>";
                }
                
                mysqli_close($conn);
            } else {
                echo "<tr><td colspan='8' style='text-align: center; color: #888;'>데이터베이스 연결 실패. <a href='third.php' style='color: var(--t1-red);'>DB 초기화를 먼저 실행해주세요.</a></td></tr>";
            }
            ?>
            </tbody>
        </table>
        
        <div class="text-center mt-40">
            <p style="color: #666;">총 <?= $totalPlayers ?>명의 선수가 등록되어 있습니다.</p>
        </div>
    </div>
    
    <script>
    function filterTable() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const positionValue = document.getElementById('positionFilter').value;
        const rows = document.querySelectorAll('#playersTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const position = row.getAttribute('data-position');
            
            const matchSearch = text.includes(searchValue);
            const matchPosition = !positionValue || position === positionValue;
            
            row.style.display = (matchSearch && matchPosition) ? '' : 'none';
        });
    }
    
    function deletePlayer(playerNum, nickname) {
        if (confirm(nickname + ' 선수를 정말 삭제하시겠습니까?')) {
            location.href = 'third1.php?delete=' + playerNum;
        }
    }
    </script>
</body>
</html>

