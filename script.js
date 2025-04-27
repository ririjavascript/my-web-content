document.addEventListener('DOMContentLoaded', () => {
    console.log('Website loaded successfully!');

    // クリックカウントを更新する関数
    async function updateClickCount(buttonId, initialLoad = false) {
        try {
            if (initialLoad) {
                // 初期表示時はGETリクエストでカウントを取得
                const response = await fetch(`get_count.php?button_id=${buttonId}`);
                const data = await response.json();
                if (data.success) {
                    const button = document.getElementById(buttonId);
                    const countSpan = button.querySelector('.click-count');
                    countSpan.textContent = `(${data.count} clicks)`;
                }
            } else {
                // クリック時はPOSTリクエストでカウントを更新
                const response = await fetch('update_count.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ button_id: buttonId })
                });

                const data = await response.json();
                if (data.success) {
                    const button = document.getElementById(buttonId);
                    const countSpan = button.querySelector('.click-count');
                    countSpan.textContent = `(${data.count} clicks)`;
                } else {
                    console.error('Error:', data.error);
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // 初期表示時にカウントを取得
    updateClickCount('getStartedBtn', true);
    updateClickCount('learnMoreBtn', true);

    // ボタンにクリックイベントを追加
    const getStartedBtn = document.getElementById('getStartedBtn');
    if (getStartedBtn) {
        getStartedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            updateClickCount('getStartedBtn');
        });
    }

    const learnMoreBtn = document.getElementById('learnMoreBtn');
    if (learnMoreBtn) {
        learnMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            updateClickCount('learnMoreBtn');
        });
    }

    // スムーズスクロール
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (!this.classList.contains('button')) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // ヘッダーのスクロール制御
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        const currentScroll = window.pageYOffset;

        if (currentScroll > lastScroll) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }

        lastScroll = currentScroll;
    });

    // セクションのフェードインアニメーション
    const sections = document.querySelectorAll('section');
    const options = {
        root: null,
        threshold: 0.1,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, options);

    sections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'all 0.5s ease-in-out';
        observer.observe(section);
    });
}); 