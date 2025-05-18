<?php
// Set time zone to IST
date_default_timezone_set('Asia/Kolkata');

// Get channel ID from query parameter
$channel_id = isset($_GET['id']) ? intval($_GET['id'])

// Fetch JSON data from the API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://jiotv.data.cdn.jio.com/apis/v1.3/getepg/get?channel_id=$channel_id&offset=0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($ch);
curl_close($ch);
$data = json_decode($json, true);

// Validate data
if (!$data || !isset($data['epg'])) {
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Error</title><link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'><style>body { background: #0F0F0F; color: #E0E0E0; display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: 'Poppins', sans-serif; } .error-container { text-align: center; background: #222222; padding: 30px; border-radius: 10px; box-shadow: 0 0 25px rgba(255, 100, 150, 0.4); } h1 { color: #FF6B8B; } p { color: #E0E0E0; }</style></head><body><div class='error-container'><h1>Oops!</h1><p>Error fetching EPG data or channel not found.</p><p>Please try a different channel ID or refresh the page.</p></div></body></html>";
    exit;
}

// Base URL for images
$base_url = "https://jiotvimages.cdn.jio.com/dare_images/shows/700/-/";

// Dynamic channel logo
$channel_name_for_logo = str_replace(' ', '_', $data['channel_name']);
$logo_url = "https://jiotvimages.cdn.jio.com/dare_images/images/{$channel_name_for_logo}.png";

// Current time in seconds
$current_time = time();

// Filter current and upcoming shows
$current_show = null;
$upcoming_shows = [];
foreach ($data['epg'] as $show) {
    $start = $show['startEpoch'] / 1000; // Convert milliseconds to seconds
    $end = $show['endEpoch'] / 1000;
    if ($start <= $current_time && $current_time < $end) {
        $current_show = $show;
    } elseif ($start > $current_time) {
        $upcoming_shows[] = $show;
    }
}

// Last updated timestamp
$last_updated = date('H:i:s, d M Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPG for <?php echo htmlspecialchars($data['channel_name']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(45deg, #A76AF6, #4C8EFF); /* Softer purple to blue */
            --accent-color:rgb(255, 79, 79); /* Vibrant coral pink */
            --background-dark: #0F0F0F; /* Slightly lighter dark for depth */
            --card-dark: #222222; /* Medium dark for cards */
            --text-light: #E0E0E0; /* Soft white */
            --text-muted: #B0B0B0; /* Light grey for secondary text */
            --shadow-light: rgba(0, 0, 0, 0.7); /* Stronger shadow for depth */
            --border-color: #444444; /* Darker border for contrast */
            --live-background: linear-gradient(90deg,rgb(255, 0, 55) 0%, #FF8E53 100%); /* Orange-pink for live */
            --refresh-button-gradient: linear-gradient(90deg, #6A11CB 0%, #2575FC 100%); /* Blue-purple for refresh */
        }

        body {
            background: var(--background-dark);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
            flex-grow: 1;
        }

        .refresh-btn {
            background: var(--refresh-button-gradient);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            opacity: 0.9;
        }

        .channel-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 20px;
            background: var(--card-dark);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-light);
            animation: fadeIn 1s ease-in;
            border: 1px solid var(--border-color);
        }

        .channel-header h1 {
            font-size: 3.2rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: glow 3s ease-in-out infinite;
            margin-bottom: 10px;
        }

        .channel-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-top: 15px;
        }

        .channel-header img {
            max-width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 25px var(--accent-color);
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            margin-bottom: 15px;
            border: 3px solid var(--accent-color); /* Added a border to logo */
        }

        .channel-header img:hover {
            transform: scale(1.08) rotate(5deg);
        }

        .current-show {
            background: linear-gradient(135deg, var(--card-dark), #2A2A2A);
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 50px;
            box-shadow: 0 12px 25px var(--shadow-light);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
            border: 2px solid var(--accent-color);
        }

        .current-show::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle at top left, rgba(255, 100, 150, 0.15) 0%, transparent 70%); /* Uses a lighter accent */
            z-index: 0;
            opacity: 0.7;
            transform: rotate(15deg);
        }

        .current-show .content-wrapper {
            position: relative;
            z-index: 1;
        }

        .current-show h2 {
            color: var(--accent-color);
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(203, 0, 44, 0.79); /* Adjusted accent shadow */
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .live-badge {
            background: var(--live-background);
            color: #fff;
            padding: 0.35rem 0.8rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 700;
            margin-left: 15px;
            animation: pulse 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
            box-shadow: 0 0 10px rgba(189, 0, 41, 0.79); /* Live badge shadow */
        }

        .current-show img {
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s ease-out;
        }

        .current-show img:hover {
            transform: scale(1.02);
        }

        .current-show h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-light);
        }

        .current-show p {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 15px;
        }

        .progress {
            background-color: #3A3A3A;
            border-radius: 10px;
            height: 12px;
            margin-top: 20px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--accent-color), #FF8E53); /* Accent to orange for progress */
            border-radius: 10px;
            transition: width 0.5s ease-out;
        }

        .upcoming-section .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 35px;
            color: var(--text-light);
            position: relative;
            text-align: center;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding-bottom: 15px;
        }

        .upcoming-section .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 5px;
            background: var(--accent-color);
            border-radius: 5px;
        }

        .upcoming-shows .card {
            background: var(--card-dark);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .upcoming-shows .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(218, 0, 47, 0.83); /* Adjusted hover shadow to accent */
            border-color: var(--accent-color);
        }

        .card-img-container {
            flex-shrink: 0;
            width: 150px;
            height: 100%;
            overflow: hidden;
            border-radius: 15px 0 0 15px;
            background: rgba(0,0,0,0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px 0 0 15px;
            transition: transform 0.3s ease;
        }

        .upcoming-shows .card:hover .card-img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.4em;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .card-text {
            font-size: 0.95em;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .badge {
            font-size: 0.8em;
            padding: 0.4em 0.8em;
            border-radius: 20px;
            margin-right: 5px;
            font-weight: 600;
        }

        .badge-genre {
            background-color: #555555;
            color: #E0E0E0;
        }

        .badge-next {
            background: linear-gradient(90deg, #1ABC9C, #2ECC71); /* Green for 'Next' */
            color: white;
            animation: pulse 1.5s infinite;
        }
        .badge-info { /* 'Soon' badge */
            background-color: #3498DB; /* Blue for 'Soon' */
            color: white;
        }


        footer {
            text-align: center;
            padding: 25px 0;
            color: #777;
            font-size: 0.85rem;
            margin-top: 40px;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid #222;
        }

        /* Keyframe Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInUp {
            from { transform: translateY(80px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes glow {
            0%, 100% { filter: brightness(100%); text-shadow: 0 0 8px rgba(167, 106, 246, 0.4); } /* Adjusted glow to primary gradient start */
            50% { filter: brightness(120%); text-shadow: 0 0 15px rgba(167, 106, 246, 0.8); }
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 39, 86, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(255, 107, 139, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 139, 0); }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .channel-header h1 {
                font-size: 2.5rem;
            }
            .channel-header h2 {
                font-size: 1.5rem;
            }
            .current-show h2 {
                font-size: 2rem;
            }
            .current-show .col-md-4, .current-show .col-md-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .current-show img {
                margin-bottom: 20px;
            }
            .upcoming-shows .card {
                flex-direction: column;
                align-items: flex-start;
            }
            .card-img-container {
                width: 100%;
                height: 200px;
                border-radius: 15px 15px 0 0;
            }
            .card-img {
                border-radius: 15px 15px 0 0;
            }
            .card-body {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .channel-header img {
                max-width: 100px;
                height: 100px;
            }
            .current-show {
                padding: 20px;
            }
            .live-badge {
                margin-left: 10px;
                padding: 0.25rem 0.6rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-right mb-4">
            <button class="refresh-btn" onclick="location.reload()">Refresh (Last: <?php echo $last_updated; ?>)</button>
        </div>
        <div class="channel-header">
            <img src="<?php echo $logo_url; ?>" alt="<?php echo htmlspecialchars($data['channel_name']); ?> Logo" class="img-fluid">
            <h1>EPG for <?php echo htmlspecialchars($data['channel_name']); ?></h1>
            <?php
            $server_date = new DateTime($data['serverDate']);
            echo "<h2>Schedule for " . $server_date->format('l, F j, Y') . "</h2>";
            ?>
        </div>

        <?php if ($current_show): ?>
            <div class="current-show" role="region" aria-label="Current Show">
                <div class="content-wrapper">
                    <h2>Now Playing <span class="live-badge">LIVE</span></h2>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <img src="<?php echo $base_url . $current_show['episodeThumbnail']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($current_show['showname']); ?>" loading="lazy">
                        </div>
                        <div class="col-md-8">
                            <h3><?php echo htmlspecialchars($current_show['showname']); ?></h3>
                            <p><?php echo htmlspecialchars($current_show['description']); ?></p>
                            <?php if (!empty($current_show['showGenre'])): ?>
                                <span class="badge badge-genre"><?php echo htmlspecialchars($current_show['showGenre'][0]); ?></span>
                            <?php endif; ?>
                            <?php if (isset($current_show['episode_num']) && $current_show['episode_num'] != -1): ?>
                                <p><strong>Episode:</strong> <?php echo $current_show['episode_num']; ?></p>
                            <?php endif; ?>
                            <?php
                            $start = $current_show['startEpoch'] / 1000;
                            $end = $current_show['endEpoch'] / 1000;
                            $total_duration = $end - $start;
                            $elapsed = $current_time - $start;
                            $progress = ($total_duration > 0) ? ($elapsed / $total_duration) * 100 : 0;
                            $progress = max(0, min(100, $progress)); // Clamp progress between 0 and 100
                            ?>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <?php
                            $remaining_time = $end - $current_time;
                            $minutes = floor($remaining_time / 60);
                            $seconds = $remaining_time % 60;
                            if ($minutes < 0) {
                                echo "<p><strong>Ended:</strong> " . abs($minutes) . " min ago</p>";
                            } else {
                                echo "<p><strong>Ends in:</strong> $minutes min $seconds sec</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="current-show" role="region" aria-label="No Current Show">
                <div class="content-wrapper">
                    <h2>No Show Currently Playing</h2>
                    <?php if (!empty($upcoming_shows)): ?>
                        <?php
                        $next_show = $upcoming_shows[0];
                        $start_time = date('H:i', $next_show['startEpoch'] / 1000);
                        echo "<p>Next show, <strong>" . htmlspecialchars($next_show['showname']) . "</strong>, starts at $start_time.</p>";
                        ?>
                    <?php else: ?>
                        <p>No upcoming shows found for today.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <section class="upcoming-section" role="region" aria-label="Upcoming Shows">
            <h2 class="section-title">Upcoming Shows</h2>
            <div class="upcoming-shows row">
                <?php foreach ($upcoming_shows as $index => $show): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-img-container">
                                <img src="<?php echo $base_url . $show['episodeThumbnail']; ?>" class="card-img" alt="<?php echo htmlspecialchars($show['showname']); ?>" loading="lazy">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($show['showtime']); ?> - 
                                    <?php echo htmlspecialchars($show['endtime']); ?> | 
                                    <?php echo htmlspecialchars($show['showname']); ?>
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($show['description']); ?></p>
                                <?php if (!empty($show['showGenre'])): ?>
                                    <span class="badge badge-genre"><?php echo htmlspecialchars($show['showGenre'][0]); ?></span>
                                <?php endif; ?>
                                <?php if (isset($show['episode_num']) && $show['episode_num'] != -1): ?>
                                    <span class="badge badge-secondary">Epi. <?php echo $show['episode_num']; ?></span>
                                <?php endif; ?>
                                <?php if ($index == 0 && !$current_show): ?>
                                    <span class="badge badge-next">Next</span>
                                <?php elseif ($index == 0 && $current_show): ?>
                                    <span class="badge badge-info">Soon</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($upcoming_shows)): ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No upcoming shows found for the remainder of today.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
    <footer>
        EPG data provided by JioTV. Last Updated: <?php echo $last_updated; ?>
    </footer>
</body>
</html>
