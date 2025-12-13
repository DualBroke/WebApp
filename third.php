<?php
    // T1 e스포츠 팀 데이터베이스 및 테이블 생성
    $conn = mysqli_connect("localhost", "root", "");
    
    if ($conn) {
        echo "<h2>T1 E-Sports 데이터베이스 초기화</h2>";
        echo "<hr>";
        echo "DB 서버 접속 성공<br><br>";
        
        // t1_db 데이터베이스 생성
        $qry = "CREATE DATABASE IF NOT EXISTS t1_db";
        $rst = mysqli_query($conn, $qry);
        if ($rst) {
            echo "t1_db 데이터베이스 생성/확인 완료<br>";
        } else {
            echo "데이터베이스 생성 실패: " . mysqli_error($conn) . "<br>";
        }
        
        // 데이터베이스 선택
        $myDB = mysqli_select_db($conn, "t1_db");
        if ($myDB) {
            echo "t1_db 데이터베이스 선택 완료<br><br>";
        } else {
            echo "데이터베이스 선택 실패<br>";
        }
        
        // ========== 1. 선수(players) 테이블 생성 ==========
        $qry = "SELECT 1 FROM information_schema.tables WHERE TABLE_SCHEMA='t1_db' AND TABLE_NAME='players'";
        $rst = mysqli_query($conn, $qry);
        $nRows = mysqli_num_rows($rst);
        
        if ($nRows > 0) {
            echo "players 테이블이 이미 존재합니다.<br>";
        } else {
            $qry = "CREATE TABLE players (
                playerNum INT AUTO_INCREMENT NOT NULL,
                sNickname VARCHAR(30) NOT NULL,
                sRealName VARCHAR(30) NOT NULL,
                sPosition VARCHAR(20) NOT NULL,
                sBirthDate DATE,
                sNationality VARCHAR(20) DEFAULT '대한민국',
                iDebutYear INT,
                sPhoto VARCHAR(200),
                sSignature VARCHAR(100),
                sMemo TEXT,
                PRIMARY KEY(playerNum)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            $rst = mysqli_query($conn, $qry);
            if ($rst) {
                echo "players 테이블 생성 완료<br>";
                
                // 선수 데이터 삽입 (2025 T1 로스터)
                $qry = "INSERT INTO players (sNickname, sRealName, sPosition, sBirthDate, sNationality, iDebutYear, sPhoto, sSignature, sMemo) VALUES
                    ('Faker', '이상혁', '미드', '1996-05-07', '대한민국', 2013, 'images/Faker.jpg', '불멸의 대마왕', 'T1의 캡틴이자 리그 오브 레전드 역사상 최고의 선수. 월드 챔피언십 6회 우승.'),
                    ('Doran', '최현준', '탑', '2000-08-27', '대한민국', 2019, 'images/Doran.jpg', '믿음직한 탑 라이너', '2025시즌 T1에 합류한 안정적인 탑 라이너.'),
                    ('Oner', '문현준', '정글', '2002-12-24', '대한민국', 2021, 'images/Oner.jpg', '정글의 지배자', '공격적인 정글링과 팀파이트 능력이 뛰어난 선수.'),
                    ('Peyz', '김수환', '원딜', '2005-12-05', '대한민국', 2023, 'images/Peyz.jpg', '차세대 원딜', '2025시즌 T1에 합류한 젊은 원거리 딜러. Gen.G에서 활약 후 이적.'),
                    ('Keria', '류민석', '서포터', '2002-10-14', '대한민국', 2020, 'images/Keria.jpg', '서폿의 신', '역대급 서포터로 평가받는 선수. 다양한 챔피언 운용 능력.')";
                
                $rst = mysqli_query($conn, $qry);
                if ($rst) {
                    echo "선수 데이터 5명 삽입 완료<br><br>";
                } else {
                    echo "선수 데이터 삽입 실패: " . mysqli_error($conn) . "<br>";
                }
            } else {
                echo "players 테이블 생성 실패: " . mysqli_error($conn) . "<br>";
            }
        }
        
        // ========== 2. 대회 기록(achievements) 테이블 생성 ==========
        $qry = "SELECT 1 FROM information_schema.tables WHERE TABLE_SCHEMA='t1_db' AND TABLE_NAME='achievements'";
        $rst = mysqli_query($conn, $qry);
        $nRows = mysqli_num_rows($rst);
        
        if ($nRows > 0) {
            echo "achievements 테이블이 이미 존재합니다.<br>";
        } else {
            $qry = "CREATE TABLE achievements (
                achieveNum INT AUTO_INCREMENT NOT NULL,
                sTournament VARCHAR(100) NOT NULL,
                iYear INT NOT NULL,
                sResult VARCHAR(30) NOT NULL,
                sMVP VARCHAR(30),
                sMemo TEXT,
                PRIMARY KEY(achieveNum)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            $rst = mysqli_query($conn, $qry);
            if ($rst) {
                echo "achievements 테이블 생성 완료<br>";
                
                // 대회 기록 삽입 - T1 주요 우승 기록
                // 월드 챔피언십: 2013, 2015, 2016, 2023, 2024, 2025 (6회)
                // LCK/OGN: 2013 Summer, 2013-2014 Winter, 2015 Spring, 2015 Summer, 
                //          2016 Spring, 2017 Spring, 2019 Spring, 2019 Summer, 
                //          2020 Spring, 2022 Spring (10회)
                $qry = "INSERT INTO achievements (sTournament, iYear, sResult, sMVP, sMemo) VALUES
                    ('월드 챔피언십', 2025, '우승', 'Gumayusi', 'T1 역대 6번째 월드 챔피언십 우승, 3연속 우승'),
                    ('월드 챔피언십', 2024, '우승', 'Faker', 'Faker 개인 5회 우승, 역대 최다 기록'),
                    ('월드 챔피언십', 2023, '우승', 'Zeus', 'T1 4번째 월드 챔피언십 우승'),
                    ('LCK 스프링', 2022, '우승', 'Faker', 'LCK/OGN 통산 10번째 우승'),
                    ('LCK 스프링', 2020, '우승', 'Faker', 'LCK 통산 9번째 우승'),
                    ('LCK 서머', 2019, '우승', 'Clid', 'LCK 통산 8번째 우승'),
                    ('LCK 스프링', 2019, '우승', 'Teddy', 'LCK 통산 7번째 우승'),
                    ('LCK 스프링', 2017, '우승', 'Faker', 'LCK 통산 6번째 우승'),
                    ('월드 챔피언십', 2016, '우승', 'Faker', '사상 최초 월드 챔피언십 3연패'),
                    ('LCK 스프링', 2016, '우승', 'Faker', 'LCK 통산 5번째 우승'),
                    ('월드 챔피언십', 2015, '우승', 'Faker', 'Faker 개인 2회 우승'),
                    ('LCK 서머', 2015, '우승', 'MaRin', 'LCK 통산 4번째 우승'),
                    ('LCK 스프링', 2015, '우승', 'Faker', 'LCK 통산 3번째 우승'),
                    ('OGN 윈터', 2014, '우승', 'Faker', 'OGN 통산 2번째 우승 (2013-2014 시즌)'),
                    ('월드 챔피언십', 2013, '우승', 'Faker', 'T1 첫 번째 월드 챔피언십 우승'),
                    ('OGN 서머', 2013, '우승', 'Faker', 'T1 첫 번째 국내 리그 우승')";
                
                $rst = mysqli_query($conn, $qry);
                if ($rst) {
                    echo "대회 기록 16개 삽입 완료<br><br>";
                } else {
                    echo "대회 기록 삽입 실패: " . mysqli_error($conn) . "<br>";
                }
            } else {
                echo "achievements 테이블 생성 실패: " . mysqli_error($conn) . "<br>";
            }
        }
        
        // ========== 3. 팀 정보(team_info) 테이블 생성 ==========
        $qry = "SELECT 1 FROM information_schema.tables WHERE TABLE_SCHEMA='t1_db' AND TABLE_NAME='team_info'";
        $rst = mysqli_query($conn, $qry);
        $nRows = mysqli_num_rows($rst);
        
        if ($nRows > 0) {
            echo "team_info 테이블이 이미 존재합니다.<br>";
        } else {
            $qry = "CREATE TABLE team_info (
                infoNum INT AUTO_INCREMENT NOT NULL,
                sTeamName VARCHAR(50) NOT NULL,
                sFullName VARCHAR(100),
                sLeague VARCHAR(50),
                iFoundYear INT,
                sHeadCoach VARCHAR(30),
                sLocation VARCHAR(50),
                sLogo VARCHAR(200),
                sMemo TEXT,
                PRIMARY KEY(infoNum)
            )";
            
            $rst = mysqli_query($conn, $qry);
            if ($rst) {
                echo "team_info 테이블 생성 완료<br>";
                
                // 팀 정보 삽입
                $qry = "INSERT INTO team_info (sTeamName, sFullName, sLeague, iFoundYear, sHeadCoach, sLocation, sLogo, sMemo) VALUES
                    ('T1', 'T1 Entertainment & Sports', 'LCK (League of Legends Champions Korea)', 2012, 'kkOma (김정균)', '대한민국 서울', 'images/t1_logo.png', 'SK텔레콤과 Comcast Spectacor의 합작 e스포츠 팀. 리그 오브 레전드 역사상 가장 성공적인 팀.')";
                
                $rst = mysqli_query($conn, $qry);
                if ($rst) {
                    echo "팀 정보 삽입 완료<br><br>";
                } else {
                    echo "팀 정보 삽입 실패: " . mysqli_error($conn) . "<br>";
                }
            } else {
                echo "team_info 테이블 생성 실패: " . mysqli_error($conn) . "<br>";
            }
        }
        
        echo "<hr>";
        echo "<h3>데이터베이스 초기화 완료!</h3>";
        echo "<p><a href='third_home.php' style='color:#e2012d; font-weight:bold; font-size:18px;'>▶ T1 홈페이지로 이동</a></p>";
        
    } else {
        echo "DB 서버 접속 실패<br>";
    }
    
    if (isset($conn) && $conn) {
        mysqli_close($conn);
    }
?>

