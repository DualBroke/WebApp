<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T1 - We Are T1</title>
    <link rel="stylesheet" href="third.css">
</head>
<body>
    <div class="t1-background"></div>
    
    <nav class="t1-navbar">
        <div class="nav-content">
            <a href="third_home.php" class="nav-logo">T1</a>
            <ul class="nav-menu">
                <li><a href="third_home.php" class="active">홈</a></li>
                <li><a href="third1.php">선수 관리</a></li>
                <li><a href="third2.php">선수 등록</a></li>
                <li><a href="third3.php">대회 기록</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="t1-container">
        <!-- 히어로 섹션 -->
        <section class="t1-hero">
            <h1>T1</h1>
            <p class="subtitle">World Championship <span class="highlight">5x Champions</span></p>
            <p style="color: #888; font-size: 1.1rem; margin-top: 20px;">
                리그 오브 레전드 역사상 가장 위대한 팀
            </p>
            <div class="mt-40">
                <a href="third1.php" class="t1-btn">선수 보기</a>
                <a href="third3.php" class="t1-btn t1-btn-secondary" style="margin-left: 15px;">대회 기록</a>
            </div>
        </section>
        
        <!-- 팀 정보 섹션 -->
        <section class="mb-40">
            <h2 class="section-title">TEAM INFO</h2>
            
            <?php
                $conn = mysqli_connect("localhost", "root", "");
                if ($conn) {
                    mysqli_select_db($conn, "t1_db");
                    $qry = "SELECT * FROM team_info LIMIT 1";
                    $rst = mysqli_query($conn, $qry);
                    
                    if ($rst && mysqli_num_rows($rst) > 0) {
                        $team = mysqli_fetch_assoc($rst);
            ?>
            <div class="team-info-section">
                <div class="info-card">
                    <div class="icon">🏆</div>
                    <h3>창단</h3>
                    <p><?= $team['iFoundYear'] ?>년</p>
                </div>
                <div class="info-card">
                    <div class="icon">🎮</div>
                    <h3>리그</h3>
                    <p><?= $team['sLeague'] ?></p>
                </div>
                <div class="info-card">
                    <div class="icon">👨‍💼</div>
                    <h3>감독</h3>
                    <p><?= $team['sHeadCoach'] ?></p>
                </div>
                <div class="info-card">
                    <div class="icon">📍</div>
                    <h3>위치</h3>
                    <p><?= $team['sLocation'] ?></p>
                </div>
            </div>
            <div class="mt-40 text-center">
                <p style="color: #888; max-width: 800px; margin: 0 auto; line-height: 1.8;">
                    <?= $team['sMemo'] ?>
                </p>
            </div>
            <?php
                    }
                    mysqli_close($conn);
                }
            ?>
        </section>
        
        <!-- 선수 섹션 -->
        <section class="mt-40 mb-40">
            <h2 class="section-title">ROSTER 2025</h2>
            
            <div class="players-grid">
            <?php
                $conn = mysqli_connect("localhost", "root", "");
                if ($conn) {
                    mysqli_select_db($conn, "t1_db");
                    $qry = "SELECT * FROM players ORDER BY FIELD(sPosition, '탑', '정글', '미드', '원딜', '서포터')";
                    $rst = mysqli_query($conn, $qry);
                    
                    if ($rst) {
                        while ($player = mysqli_fetch_assoc($rst)) {
            ?>
                <div class="player-card">
                    <div class="player-photo">
                        <?php 
                            // DB에 저장된 경로 또는 닉네임 기반 이미지 경로 시도
                            $imgPath = $player['sPhoto'];
                            $altImgPath = 'images/' . $player['sNickname'] . '.jpg';
                            
                            if ($imgPath && file_exists($imgPath)) {
                                echo '<img src="' . $imgPath . '" alt="' . $player['sNickname'] . '">';
                            } elseif (file_exists($altImgPath)) {
                                echo '<img src="' . $altImgPath . '" alt="' . $player['sNickname'] . '">';
                            } else {
                                echo '<span class="placeholder">🎮</span>';
                            }
                        ?>
                    </div>
                    <div class="player-info">
                        <div class="player-nickname"><?= $player['sNickname'] ?></div>
                        <div class="player-realname"><?= $player['sRealName'] ?></div>
                        <span class="player-position"><?= $player['sPosition'] ?></span>
                        <p class="player-signature">"<?= $player['sSignature'] ?>"</p>
                    </div>
                </div>
            <?php
                        }
                    }
                    mysqli_close($conn);
                }
            ?>
            </div>
            
            <div class="text-center mt-40">
                <a href="third1.php" class="t1-btn">전체 선수 관리 →</a>
            </div>
        </section>
        
        <!-- 최근 업적 섹션 -->
        <section class="mt-40">
            <h2 class="section-title">ACHIEVEMENTS</h2>
            
            <?php
                $conn = mysqli_connect("localhost", "root", "");
                if ($conn) {
                    mysqli_select_db($conn, "t1_db");
                    $qry = "SELECT * FROM achievements ORDER BY iYear DESC, achieveNum DESC LIMIT 5";
                    $rst = mysqli_query($conn, $qry);
                    
                    if ($rst) {
                        while ($achieve = mysqli_fetch_assoc($rst)) {
            ?>
            <div class="achievement-card">
                <div class="achievement-year"><?= $achieve['iYear'] ?></div>
                <div class="achievement-info">
                    <h3><?= $achieve['sTournament'] ?></h3>
                    <span class="achievement-result"><?= $achieve['sResult'] ?></span>
                    <p class="achievement-mvp">MVP: <?= $achieve['sMVP'] ?> | <?= $achieve['sMemo'] ?></p>
                </div>
            </div>
            <?php
                        }
                    }
                    mysqli_close($conn);
                }
            ?>
            
            <div class="text-center mt-40">
                <a href="third3.php" class="t1-btn t1-btn-secondary">전체 대회 기록 보기 →</a>
            </div>
        </section>
    </div>
    
    <footer style="text-align: center; padding: 40px; color: #555; border-top: 1px solid #333; margin-top: 60px;">
        <p>© 2024 T1 Fan Page - 웹응용 프로젝트</p>
        <p style="margin-top: 10px; font-size: 0.9rem;">
            <a href="third.php" style="color: var(--t1-red);">데이터베이스 초기화</a>
        </p>
    </footer>
</body>
</html>

