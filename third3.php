<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T1 - 대회 기록</title>
    <link rel="stylesheet" href="third.css">
    <style>
        .trophy-counter {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-bottom: 60px;
            flex-wrap: wrap;
        }
        .trophy-item {
            text-align: center;
            padding: 30px;
            background: linear-gradient(145deg, var(--t1-dark), var(--t1-gray));
            border: 2px solid var(--t1-gray);
            border-radius: 20px;
            min-width: 180px;
            transition: all 0.3s ease;
        }
        .trophy-item:hover {
            border-color: var(--t1-gold);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.2);
        }
        .trophy-icon {
            font-size: 4rem;
            margin-bottom: 15px;
        }
        .trophy-count {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--t1-gold);
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }
        .trophy-label {
            color: #888;
            font-size: 1rem;
            margin-top: 10px;
            letter-spacing: 0.1em;
        }
        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--t1-gold), var(--t1-red), var(--t1-gold));
            border-radius: 2px;
        }
        .timeline-item {
            display: flex;
            justify-content: flex-end;
            padding-right: 50%;
            position: relative;
            margin-bottom: 40px;
        }
        .timeline-item:nth-child(even) {
            justify-content: flex-start;
            padding-right: 0;
            padding-left: 50%;
        }
        .timeline-content {
            background: var(--t1-dark);
            border: 1px solid var(--t1-gray);
            border-radius: 15px;
            padding: 25px;
            max-width: 400px;
            margin: 0 30px;
            position: relative;
            transition: all 0.3s ease;
        }
        .timeline-content:hover {
            border-color: var(--t1-gold);
            box-shadow: 0 5px 20px rgba(255, 215, 0, 0.2);
        }
        .timeline-dot {
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--t1-gold);
            border: 4px solid var(--t1-black);
            border-radius: 50%;
            left: 50%;
            transform: translateX(-50%);
            top: 30px;
            z-index: 1;
        }
        .timeline-year {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--t1-gold);
            margin-bottom: 10px;
        }
        .timeline-tournament {
            font-size: 1.2rem;
            color: var(--t1-white);
            margin-bottom: 10px;
        }
        .timeline-result {
            display: inline-block;
            background: var(--t1-gold);
            color: var(--t1-black);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .timeline-mvp {
            color: #888;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        .timeline-memo {
            color: #aaa;
            margin-top: 10px;
            font-size: 0.85rem;
            line-height: 1.6;
        }
        .add-achievement-btn {
            text-align: center;
            margin: 40px 0;
        }
        @media (max-width: 768px) {
            .timeline::before {
                left: 20px;
            }
            .timeline-item,
            .timeline-item:nth-child(even) {
                padding-left: 60px;
                padding-right: 0;
                justify-content: flex-start;
            }
            .timeline-dot {
                left: 20px;
            }
            .timeline-content {
                margin: 0;
            }
        }
        
        /* 대회 추가 모달 */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: var(--t1-dark);
            border: 1px solid var(--t1-red);
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            color: var(--t1-red);
            margin-bottom: 30px;
            text-align: center;
        }
        .modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 2rem;
            color: #666;
            cursor: pointer;
            transition: color 0.3s;
        }
        .modal-close:hover {
            color: var(--t1-red);
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
                <li><a href="third1.php">선수 관리</a></li>
                <li><a href="third2.php">선수 등록</a></li>
                <li><a href="third3.php" class="active">대회 기록</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="t1-container">
        <h1 class="section-title">HALL OF FAME</h1>
        
        <?php
            $conn = mysqli_connect("localhost", "root", "");
            $worldsCount = 0;
            $lckCount = 0;
            $totalCount = 0;
            
            if ($conn) {
                mysqli_select_db($conn, "t1_db");
                
                // 대회 추가 처리
                if (isset($_POST['addAchieve'])) {
                    $tournament = mysqli_real_escape_string($conn, $_POST['tournament']);
                    $year = intval($_POST['year']);
                    $result = mysqli_real_escape_string($conn, $_POST['result']);
                    $mvp = mysqli_real_escape_string($conn, $_POST['mvp']);
                    $memo = mysqli_real_escape_string($conn, $_POST['memo']);
                    
                    $qry = "INSERT INTO achievements (sTournament, iYear, sResult, sMVP, sMemo)
                            VALUES ('$tournament', $year, '$result', '$mvp', '$memo')";
                    $rst = mysqli_query($conn, $qry);
                    if ($rst) {
                        echo "<script>alert('대회 기록이 추가되었습니다.');</script>";
                    }
                }
                
                // 삭제 처리
                if (isset($_GET['delete'])) {
                    $delNum = intval($_GET['delete']);
                    $qry = "DELETE FROM achievements WHERE achieveNum = " . $delNum;
                    mysqli_query($conn, $qry);
                }
                
                // 통계 조회 (LCK + OGN 모두 포함)
                $qry = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN sTournament LIKE '%월드%' AND sResult = '우승' THEN 1 ELSE 0 END) as worlds,
                        SUM(CASE WHEN (sTournament LIKE '%LCK%' OR sTournament LIKE '%OGN%') AND sResult = '우승' THEN 1 ELSE 0 END) as lck
                        FROM achievements WHERE sResult = '우승'";
                $rst = mysqli_query($conn, $qry);
                if ($rst) {
                    $stats = mysqli_fetch_assoc($rst);
                    $worldsCount = $stats['worlds'] ?: 0;
                    $lckCount = $stats['lck'] ?: 0;
                    $totalCount = $stats['total'] ?: 0;
                }
        ?>
        
        <!-- 트로피 카운터 -->
        <div class="trophy-counter">
            <div class="trophy-item">
                <div class="trophy-icon">🏆</div>
                <div class="trophy-count"><?= $worldsCount ?></div>
                <div class="trophy-label">WORLDS</div>
            </div>
            <div class="trophy-item">
                <div class="trophy-icon">🥇</div>
                <div class="trophy-count"><?= $lckCount ?></div>
                <div class="trophy-label">LCK</div>
            </div>
            <div class="trophy-item">
                <div class="trophy-icon">⭐</div>
                <div class="trophy-count"><?= $totalCount ?></div>
                <div class="trophy-label">TOTAL</div>
            </div>
        </div>
        
        <div class="add-achievement-btn">
            <button class="t1-btn" onclick="openModal()">+ 대회 기록 추가</button>
        </div>
        
        <!-- 타임라인 -->
        <div class="timeline">
        <?php
            $qry = "SELECT * FROM achievements ORDER BY iYear DESC, achieveNum DESC";
            $rst = mysqli_query($conn, $qry);
            
            if ($rst && mysqli_num_rows($rst) > 0) {
                while ($achieve = mysqli_fetch_assoc($rst)) {
        ?>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-year"><?= $achieve['iYear'] ?></div>
                    <div class="timeline-tournament"><?= $achieve['sTournament'] ?></div>
                    <span class="timeline-result"><?= $achieve['sResult'] ?></span>
                    <div class="timeline-mvp">🎖️ MVP: <?= $achieve['sMVP'] ?></div>
                    <div class="timeline-memo"><?= $achieve['sMemo'] ?></div>
                    <div style="margin-top: 15px;">
                        <button class="t1-btn t1-btn-small t1-btn-secondary" 
                                onclick="if(confirm('이 기록을 삭제하시겠습니까?')) location.href='third3.php?delete=<?= $achieve['achieveNum'] ?>'">
                            삭제
                        </button>
                    </div>
                </div>
            </div>
        <?php
                }
            } else {
                echo "<div class='text-center' style='padding: 60px; color: #666;'>등록된 대회 기록이 없습니다.</div>";
            }
            
            mysqli_close($conn);
        } else {
            echo "<div class='text-center' style='padding: 60px; color: #666;'>데이터베이스 연결 실패. <a href='third.php' style='color: var(--t1-red);'>DB 초기화를 먼저 실행해주세요.</a></div>";
        }
        ?>
        </div>
    </div>
    
    <!-- 대회 추가 모달 -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h2 class="modal-title">대회 기록 추가</h2>
            <form method="post" action="third3.php">
                <div class="form-group">
                    <label>대회명 *</label>
                    <select name="tournament" required>
                        <option value="">대회 선택</option>
                        <option value="월드 챔피언십">월드 챔피언십 (Worlds)</option>
                        <option value="LCK 스프링">LCK 스프링</option>
                        <option value="LCK 서머">LCK 서머</option>
                        <option value="MSI">MSI (Mid-Season Invitational)</option>
                        <option value="아시안게임">아시안게임</option>
                        <option value="올스타">올스타</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>년도 *</label>
                        <input type="number" name="year" min="2012" max="2030" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>결과 *</label>
                        <select name="result" required>
                            <option value="우승">🥇 우승</option>
                            <option value="준우승">🥈 준우승</option>
                            <option value="4강">4강</option>
                            <option value="8강">8강</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>MVP</label>
                    <input type="text" name="mvp" placeholder="예: Faker">
                </div>
                <div class="form-group">
                    <label>메모</label>
                    <textarea name="memo" rows="3" placeholder="대회 관련 메모..."></textarea>
                </div>
                <div class="text-center mt-20">
                    <button type="submit" name="addAchieve" class="t1-btn">기록 추가</button>
                    <button type="button" class="t1-btn t1-btn-secondary" onclick="closeModal()">취소</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    function openModal() {
        document.getElementById('addModal').classList.add('active');
    }
    
    function closeModal() {
        document.getElementById('addModal').classList.remove('active');
    }
    
    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    
    // 모달 외부 클릭시 닫기
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    </script>
</body>
</html>

