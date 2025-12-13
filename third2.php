<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T1 - 선수 등록/편집</title>
    <link rel="stylesheet" href="third.css">
    <style>
        .photo-preview {
            width: 200px;
            height: 200px;
            border: 3px dashed var(--t1-gray);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--t1-gray);
        }
        .photo-preview:hover {
            border-color: var(--t1-red);
        }
        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-preview .placeholder-text {
            color: #666;
            text-align: center;
        }
        .photo-preview .placeholder-text span {
            font-size: 3rem;
            display: block;
            margin-bottom: 10px;
        }
        #fileInput {
            display: none;
        }
        .player-detail-card {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        @media (max-width: 768px) {
            .player-detail-card {
                grid-template-columns: 1fr;
            }
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
                <li><a href="third2.php" class="active">선수 등록</a></li>
                <li><a href="third3.php">대회 기록</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="t1-container">
        <?php
            $conn = mysqli_connect("localhost", "root", "");
            $isEdit = false;
            $player = null;
            $playerNum = null;
            
            // 기존 데이터 불러오기 (편집 모드)
            if (isset($_GET['playerNum']) && $conn) {
                $playerNum = intval($_GET['playerNum']);
                mysqli_select_db($conn, "t1_db");
                $qry = "SELECT * FROM players WHERE playerNum = " . $playerNum;
                $rst = mysqli_query($conn, $qry);
                if ($rst && mysqli_num_rows($rst) > 0) {
                    $player = mysqli_fetch_assoc($rst);
                    $isEdit = true;
                }
            }
            
            // 저장 처리
            if (isset($_POST['submit']) && $conn) {
                mysqli_select_db($conn, "t1_db");
                
                $sNickname = mysqli_real_escape_string($conn, $_POST['sNickname']);
                $sRealName = mysqli_real_escape_string($conn, $_POST['sRealName']);
                $sPosition = mysqli_real_escape_string($conn, $_POST['sPosition']);
                $sBirthDate = mysqli_real_escape_string($conn, $_POST['sBirthDate']);
                $sNationality = mysqli_real_escape_string($conn, $_POST['sNationality']);
                $iDebutYear = intval($_POST['iDebutYear']);
                $sSignature = mysqli_real_escape_string($conn, $_POST['sSignature']);
                $sMemo = mysqli_real_escape_string($conn, $_POST['sMemo']);
                $sPhoto = isset($_POST['sPhoto']) ? mysqli_real_escape_string($conn, $_POST['sPhoto']) : '';
                
                if (isset($_POST['playerNum']) && $_POST['playerNum'] != '') {
                    // UPDATE
                    $editNum = intval($_POST['playerNum']);
                    $qry = "UPDATE players SET 
                            sNickname = '$sNickname',
                            sRealName = '$sRealName',
                            sPosition = '$sPosition',
                            sBirthDate = '$sBirthDate',
                            sNationality = '$sNationality',
                            iDebutYear = $iDebutYear,
                            sSignature = '$sSignature',
                            sMemo = '$sMemo',
                            sPhoto = '$sPhoto'
                            WHERE playerNum = $editNum";
                    $rst = mysqli_query($conn, $qry);
                    if ($rst) {
                        echo "<script>alert('선수 정보가 수정되었습니다.'); location.href='third1.php';</script>";
                    } else {
                        echo "<script>alert('수정 실패: " . mysqli_error($conn) . "');</script>";
                    }
                } else {
                    // INSERT
                    $qry = "INSERT INTO players (sNickname, sRealName, sPosition, sBirthDate, sNationality, iDebutYear, sSignature, sMemo, sPhoto)
                            VALUES ('$sNickname', '$sRealName', '$sPosition', '$sBirthDate', '$sNationality', $iDebutYear, '$sSignature', '$sMemo', '$sPhoto')";
                    $rst = mysqli_query($conn, $qry);
                    if ($rst) {
                        echo "<script>alert('새 선수가 등록되었습니다.'); location.href='third1.php';</script>";
                    } else {
                        echo "<script>alert('등록 실패: " . mysqli_error($conn) . "');</script>";
                    }
                }
            }
        ?>
        
        <h1 class="section-title"><?= $isEdit ? 'EDIT PLAYER' : 'NEW PLAYER' ?></h1>
        
        <form method="post" action="third2.php" class="t1-form" id="playerForm">
            <input type="hidden" name="playerNum" value="<?= $playerNum ?>">
            <input type="hidden" name="sPhoto" id="sPhotoInput" value="<?= $player ? $player['sPhoto'] : '' ?>">
            
            <h2 class="form-title"><?= $isEdit ? $player['sNickname'] . ' 선수 정보 수정' : '신규 선수 등록' ?></h2>
            
            <div class="player-detail-card">
                <!-- 사진 영역 -->
                <div>
                    <label for="fileInput" class="photo-preview" id="photoPreview">
                        <?php if ($player && $player['sPhoto']) { ?>
                            <img src="<?= $player['sPhoto'] ?>" alt="선수 사진" id="previewImg">
                        <?php } else { ?>
                            <div class="placeholder-text" id="placeholderText">
                                <span>📷</span>
                                클릭하여 사진 선택
                            </div>
                        <?php } ?>
                    </label>
                    <input type="file" id="fileInput" accept="image/*">
                    <p style="color: #666; font-size: 0.85rem; margin-top: 10px; text-align: center;">
                        권장: 200x200px
                    </p>
                </div>
                
                <!-- 기본 정보 -->
                <div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sNickname">닉네임 (게임명) *</label>
                            <input type="text" id="sNickname" name="sNickname" required 
                                   value="<?= $player ? $player['sNickname'] : '' ?>" placeholder="예: Faker">
                        </div>
                        <div class="form-group">
                            <label for="sRealName">실명 *</label>
                            <input type="text" id="sRealName" name="sRealName" required 
                                   value="<?= $player ? $player['sRealName'] : '' ?>" placeholder="예: 이상혁">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sPosition">포지션 *</label>
                            <select id="sPosition" name="sPosition" required>
                                <option value="">포지션 선택</option>
                                <option value="탑" <?= ($player && $player['sPosition'] == '탑') ? 'selected' : '' ?>>탑 (Top)</option>
                                <option value="정글" <?= ($player && $player['sPosition'] == '정글') ? 'selected' : '' ?>>정글 (Jungle)</option>
                                <option value="미드" <?= ($player && $player['sPosition'] == '미드') ? 'selected' : '' ?>>미드 (Mid)</option>
                                <option value="원딜" <?= ($player && $player['sPosition'] == '원딜') ? 'selected' : '' ?>>원딜 (ADC)</option>
                                <option value="서포터" <?= ($player && $player['sPosition'] == '서포터') ? 'selected' : '' ?>>서포터 (Support)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sNationality">국적</label>
                            <input type="text" id="sNationality" name="sNationality" 
                                   value="<?= $player ? $player['sNationality'] : '대한민국' ?>" placeholder="예: 대한민국">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sBirthDate">생년월일</label>
                            <input type="date" id="sBirthDate" name="sBirthDate" 
                                   value="<?= $player ? $player['sBirthDate'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="iDebutYear">데뷔년도</label>
                            <input type="number" id="iDebutYear" name="iDebutYear" min="2000" max="2030" 
                                   value="<?= $player ? $player['iDebutYear'] : date('Y') ?>" placeholder="예: 2020">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="sSignature">시그니처 (별명/별칭)</label>
                    <input type="text" id="sSignature" name="sSignature" 
                           value="<?= $player ? $player['sSignature'] : '' ?>" placeholder="예: 불멸의 대마왕">
                </div>
            </div>
            
            <div class="form-group">
                <label for="sMemo">메모 (선수 소개)</label>
                <textarea id="sMemo" name="sMemo" rows="4" placeholder="선수에 대한 소개를 입력하세요..."><?= $player ? $player['sMemo'] : '' ?></textarea>
            </div>
            
            <div class="form-row mt-40" style="justify-content: center; gap: 20px;">
                <button type="submit" name="submit" class="t1-btn">
                    <?= $isEdit ? '✓ 수정 완료' : '+ 선수 등록' ?>
                </button>
                <a href="third1.php" class="t1-btn t1-btn-secondary">취소</a>
                <?php if ($isEdit) { ?>
                <button type="button" class="t1-btn" style="background: linear-gradient(135deg, #666, #444);" 
                        onclick="if(confirm('정말 삭제하시겠습니까?')) location.href='third1.php?delete=<?= $playerNum ?>'">
                    삭제
                </button>
                <?php } ?>
            </div>
        </form>
        
        <?php
            if ($conn) mysqli_close($conn);
        ?>
    </div>
    
    <script>
    // 파일 선택 시 미리보기
    document.getElementById('fileInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('placeholderText');
                
                // 기존 이미지 또는 placeholder 제거
                if (placeholder) placeholder.remove();
                let img = document.getElementById('previewImg');
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'previewImg';
                    preview.appendChild(img);
                }
                img.src = event.target.result;
                
                // hidden input에 이미지 경로 저장 (실제로는 서버에 업로드 필요)
                document.getElementById('sPhotoInput').value = 'images/' + file.name;
            };
            
            reader.readAsDataURL(file);
        }
    });
    
    // 폼 유효성 검사
    document.getElementById('playerForm').addEventListener('submit', function(e) {
        const nickname = document.getElementById('sNickname').value.trim();
        const realname = document.getElementById('sRealName').value.trim();
        const position = document.getElementById('sPosition').value;
        
        if (!nickname || !realname || !position) {
            e.preventDefault();
            alert('닉네임, 실명, 포지션은 필수 입력 항목입니다.');
            return false;
        }
    });
    </script>
</body>
</html>

