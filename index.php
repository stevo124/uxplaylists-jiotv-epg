
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JioTV - Live TV</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff00ff;
            --secondary: #00ccff;
            --background: #121212;
            --card-bg: rgba(255, 255, 255, 0.05);
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: #fff;
            overflow-x: hidden;
        }

        .header {
            background: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: glow 3s ease-in-out infinite;
        }
        .header span {
            font-size: 1.5rem;
            color: #e0e0e0;
            transition: transform 0.3s ease;
        }
        .header span:hover {
            transform: rotate(360deg);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: var(--card-bg);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .search-bar:hover, .search-bar:focus-within {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1rem;
            color: #fff;
            background: transparent;
            padding: 0.5rem;
        }
        .search-bar input::placeholder {
            color: #a0a0a0;
        }
        .search-bar button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #a0a0a0;
            transition: color 0.3s ease;
        }
        .search-bar button:hover {
            color: var(--primary);
        }

        .filter-dropdown {
            background: var(--card-bg);
            border-radius: 9999px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 1.5rem;
            width: 220px;
            color: #fff;
            font-size: 1rem;
            border: none;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="white" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 1rem center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .filter-dropdown:hover, .filter-dropdown:focus {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 1.5rem 0 1rem;
            color: #fff;
            position: relative;
            animation: slideIn 0.5s ease-out;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .favorite-section {
            background: linear-gradient(145deg, rgba(255, 0, 255, 0.15), rgba(0, 204, 255, 0.15));
            border-radius: 24px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(12px);
            display: none;
        }
        .favorite-grid {
            display: flex;
            overflow-x: auto;
            gap: 1rem;
            padding-bottom: 1rem;
            scrollbar-width: thin;
            scrollbar-color: var(--primary) #2c2c2c;
        }
        .favorite-grid::-webkit-scrollbar {
            height: 8px;
        }
        .favorite-grid::-webkit-scrollbar-track {
            background: #2c2c2c;
            border-radius: 4px;
        }
        .favorite-grid::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
        .favorite-card {
            flex: 0 0 220px;
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            border: 2px solid transparent;
        }
        .favorite-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
            border-color: var(--primary);
        }
        .favorite-card img {
            width: 100%;
            height: 110px;
            object-fit: contain;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        .favorite-card:hover img {
            transform: scale(1.05);
        }
        .favorite-card p {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .channel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
            animation: slideIn 0.5s ease-out;
        }
        @media (max-width: 640px) {
            .channel-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
            .favorite-card {
                flex: 0 0 180px;
            }
        }

        .channel-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 0.75rem;
            text-align: center;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .channel-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .channel-card img {
            width: 100%;
            height: 90px;
            object-fit: contain;
            margin-bottom: 0.5rem;
            border-radius: 6px;
            transition: transform 0.3s ease;
        }
        .channel-card:hover img {
            transform: scale(1.08);
        }
        .channel-card p {
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .hd-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: linear-gradient(45deg, #ffd700, #ffaa00);
            color: #000;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            animation: bounce 2s infinite;
        }

        .favorite-btn {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            cursor: pointer;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .favorite-btn svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: #a0a0a0;
            stroke-width: 2;
            transition: all 0.3s ease;
        }
        .favorite-btn.active svg {
            fill: #ffd700;
            stroke: #ffd700;
            animation: starPulse 0.4s ease;
        }
        .favorite-btn:hover {
            transform: scale(1.15);
            background: rgba(255, 255, 255, 0.2);
        }
        .favorite-btn.active:hover svg {
            filter: drop-shadow(0 0 8px #ffd700);
        }

        .skeleton-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 0.75rem;
            text-align: center;
            animation: pulse 1.8s infinite;
        }
        .skeleton-card .skeleton-img {
            width: 100%;
            height: 90px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }
        .skeleton-card .skeleton-text {
            width: 80%;
            height: 14px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            margin: 0 auto;
        }

        .error-message {
            color: #ff5555;
            text-align: center;
            font-size: 1rem;
            margin: 1.5rem 0;
            padding: 1rem;
            background: rgba(255, 85, 85, 0.1);
            border-radius: 8px;
            animation: slideIn 0.5s ease;
        }
        .error-message a {
            color: #00ccff;
            text-decoration: underline;
            cursor: pointer;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes glow {
            0%, 100% { filter: brightness(100%); }
            50% { filter: brightness(120%); }
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes starPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        .channel-card img:not([src]), .favorite-card img:not([src]) {
            background: rgba(255, 255, 255, 0.08);
            animation: pulse 1.8s infinite;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>JIO-TV</h1>
        <span aria-label="TV Icon">📺</span>
    </header>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search channels..." oninput="debouncedFilterChannels()" aria-label="Search channels">
            <button onclick="clearSearch()" aria-label="Clear search">✕</button>
        </div>

        <select id="genre-filter" class="filter-dropdown" onchange="filterByGenre()" aria-label="Filter by genre">
            <option value="All">All Genres</option>
        </select>

        <div class="favorite-section" id="favorite-section">
            <div class="section-title">Favorites</div>
            <div class="favorite-grid" id="favorite-grid"></div>
        </div>

        <div class="section-title">All Channels</div>
        <div class="channel-grid" id="channel-grid"></div>
    </div>

    <script>
        let allChannels = [];
        const mockIp = 'mock_ip_123';
        const favoritesKey = `favorites_${mockIp}`;
        let favorites = JSON.parse(localStorage.getItem(favoritesKey)) || [];
        let clickedStars = new Set(favorites);
        const channelsCacheKey = 'jiotv_channels_cache';
        const cacheTimeKey = 'jiotv_channels_cache_time';
        const cacheDuration = 24 * 60 * 60 * 1000; // 1 day

        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Load channels from JSON URL
        async function loadChannels() {
            const channelGrid = document.getElementById('channel-grid');
            
            // Show skeleton screen
            channelGrid.innerHTML = '';
            for (let i = 0; i < 8; i++) {
                const skeleton = document.createElement('div');
                skeleton.className = 'skeleton-card';
                skeleton.innerHTML = `
                    <div class="skeleton-img"></div>
                    <div class="skeleton-text"></div>
                `;
                channelGrid.appendChild(skeleton);
            }

            // Check cache
            const cachedChannels = localStorage.getItem(channelsCacheKey);
            const cachedTime = localStorage.getItem(cacheTimeKey);
            if (cachedChannels && cachedTime && (Date.now() - parseInt(cachedTime)) < cacheDuration) {
                allChannels = JSON.parse(cachedChannels);
                populateGenres();
                renderChannels();
                return;
            }

            // Fetch from new JSON URL
            const jsonUrl = 'https://raw.githubusercontent.com/Jitendraunatti/jio-widevine/refs/heads/main/Channel.json';
            try {
                console.log('Attempting to fetch channels from:', jsonUrl);
                const response = await fetch(jsonUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                console.log('Fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Fetched data length:', data.length);
                if (!Array.isArray(data) || data.length === 0) {
                    throw new Error('Invalid or empty channel data');
                }
                // Map the JSON structure to the expected format
                allChannels = data.map(channel => ({
                    id: channel.id,
                    title: channel.title || 'Unknown Channel',
                    logo: channel.logo || 'https://via.placeholder.com/100x100?text=No+Image',
                    genre: channel.genre || 'Unknown',
                    language: channel.language || 'Unknown',
                    HD: channel.HD === true,
                    slug: channel.slug || channel.title.toLowerCase().replace(/\s+/g, '_'),
                    catchup: channel.catchup === true
                }));
                localStorage.setItem(channelsCacheKey, JSON.stringify(allChannels));
                localStorage.setItem(cacheTimeKey, Date.now());
                populateGenres();
                renderChannels();
            } catch (error) {
                console.error('Fetch error:', error.message);
                channelGrid.innerHTML = `<p class="error-message">Failed to load channels: ${error.message}. This may be due to CORS restrictions or the server being unavailable. <a href="#" onclick="loadChannels()">Retry</a></p>`;
            }
        }

        // Populate genre dropdown
        function populateGenres() {
            const genres = [...new Set(allChannels.map(channel => channel.genre).filter(genre => genre && genre !== 'Unknown'))].sort();
            const genreFilter = document.getElementById('genre-filter');
            genreFilter.innerHTML = '<option value="All">All Genres</option>';
            genres.forEach(genre => {
                const option = document.createElement('option');
                option.value = genre;
                option.textContent = genre;
                genreFilter.appendChild(option);
            });
        }

        // Render channels and favorites
        function renderChannels() {
            const channelGrid = document.getElementById('channel-grid');
            const favoriteGrid = document.getElementById('favorite-grid');
            const favoriteSection = document.getElementById('favorite-section');
            const searchQuery = document.getElementById('search-input').value.toLowerCase();
            const selectedGenre = document.getElementById('genre-filter').value;

            channelGrid.innerHTML = '';
            favoriteGrid.innerHTML = '';

            let filteredChannels = allChannels;
            if (searchQuery) {
                filteredChannels = filteredChannels.filter(channel => 
                    channel.title?.toLowerCase().includes(searchQuery)
                );
            }
            if (selectedGenre !== 'All') {
                filteredChannels = filteredChannels.filter(channel => 
                    channel.genre === selectedGenre
                );
            }

            const favoritedChannels = filteredChannels.filter(channel => favorites.includes(channel.id));
            const nonFavoritedChannels = filteredChannels.filter(channel => !favorites.includes(channel.id));

            favoriteSection.style.display = favoritedChannels.length > 0 ? 'block' : 'none';

            favoritedChannels.forEach((channel, index) => {
                const card = createFavoriteCard(channel, index);
                favoriteGrid.appendChild(card);
            });

            nonFavoritedChannels.forEach((channel, index) => {
                const card = createChannelCard(channel, index);
                channelGrid.appendChild(card);
            });

            if (filteredChannels.length === 0 && favoritedChannels.length === 0 && allChannels.length > 0) {
                channelGrid.innerHTML = '<p class="error-message">No channels found for the selected filters.</p>';
            }
        }

        // Create a channel card
        function createChannelCard(channel, index) {
            const card = document.createElement('div');
            card.className = 'channel-card';
            card.style.animationDelay = `${index * 0.05}s`;
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.setAttribute('aria-label', `View ${channel.title || 'channel'}`);

            card.onclick = (e) => {
                if (e.target.closest('.favorite-btn')) return;
                window.open(`channel.php?id=${channel.id}`, '_blank');
            };
            card.onkeydown = (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    window.open(`channel.php?id=${channel.id}`, '_blank');
                }
            };

            const img = document.createElement('img');
            img.dataset.src = channel.logo || 'https://via.placeholder.com/100x100?text=No+Image';
            img.alt = channel.title || 'Channel';
            img.loading = 'lazy';
            card.appendChild(img);

            const title = document.createElement('p');
            title.textContent = channel.title || 'Unknown Channel';
            card.appendChild(title);

            if (channel.HD) {
                const badge = document.createElement('span');
                badge.className = 'hd-badge';
                badge.textContent = 'HD';
                card.appendChild(badge);
            }

            const favBtn = document.createElement('button');
            favBtn.className = 'favorite-btn';
            favBtn.setAttribute('aria-label', favorites.includes(channel.id) ? `Remove ${channel.title} from favorites` : `Add ${channel.title} to favorites`);
            favBtn.innerHTML = `
                <svg viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            `;
            if (clickedStars.has(channel.id)) {
                favBtn.classList.add('active');
            }
            favBtn.onclick = (e) => {
                e.stopPropagation();
                toggleFavorite(channel.id, favBtn, channel.title);
            };
            favBtn.onkeydown = (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleFavorite(channel.id, favBtn, channel.title);
                }
            };
            card.appendChild(favBtn);

            // Lazy load images
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        observer.unobserve(img);
                    }
                });
            }, { rootMargin: '100px' });
            observer.observe(img);

            return card;
        }

        // Create a favorite card
        function createFavoriteCard(channel, index) {
            const card = document.createElement('div');
            card.className = 'favorite-card';
            card.style.animationDelay = `${index * 0.05}s`;
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.setAttribute('aria-label', `View ${channel.title || 'channel'}`);

            card.onclick = (e) => {
                if (e.target.closest('.favorite-btn')) return;
                window.open(`channel.php?id=${channel.id}`, '_blank');
            };
            card.onkeydown = (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    window.open(`channel.php?id=${channel.id}`, '_blank');
                }
            };

            const img = document.createElement('img');
            img.dataset.src = channel.logo || 'https://via.placeholder.com/100x100?text=No+Image';
            img.alt = channel.title || 'Channel';
            img.loading = 'lazy';
            card.appendChild(img);

            const title = document.createElement('p');
            title.textContent = channel.title || 'Unknown Channel';
            card.appendChild(title);

            if (channel.HD) {
                const badge = document.createElement('span');
                badge.className = 'hd-badge';
                badge.textContent = 'HD';
                card.appendChild(badge);
            }

            const favBtn = document.createElement('button');
            favBtn.className = 'favorite-btn active';
            favBtn.setAttribute('aria-label', `Remove ${channel.title} from favorites`);
            favBtn.innerHTML = `
                <svg viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            `;
            favBtn.onclick = (e) => {
                e.stopPropagation();
                toggleFavorite(channel.id, favBtn, channel.title);
            };
            favBtn.onkeydown = (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleFavorite(channel.id, favBtn, channel.title);
                }
            };
            card.appendChild(favBtn);

            // Lazy load images
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        observer.unobserve(img);
                    }
                });
            }, { rootMargin: '100px' });
            observer.observe(img);

            return card;
        }

        // Toggle favorite status
        function toggleFavorite(channelId, favBtn, channelTitle) {
            if (favorites.includes(channelId)) {
                favorites = favorites.filter(id => id !== channelId);
                clickedStars.delete(channelId);
                favBtn.classList.remove('active');
                favBtn.setAttribute('aria-label', `Add ${channelTitle} to favorites`);
            } else {
                favorites.push(channelId);
                clickedStars.add(channelId);
                favBtn.classList.add('active');
                favBtn.setAttribute('aria-label', `Remove ${channelTitle} from favorites`);
            }
            localStorage.setItem(favoritesKey, JSON.stringify(favorites));
            renderChannels();
        }

        // Filter channels by search input
        const debouncedFilterChannels = debounce(renderChannels, 300);

        // Clear search input
        function clearSearch() {
            document.getElementById('search-input').value = '';
            renderChannels();
        }

        // Filter channels by genre
        function filterByGenre() {
            renderChannels();
        }

        // Initialize
        loadChannels();
    </script>
</body>
</html>
