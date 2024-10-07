document.addEventListener('DOMContentLoaded', () => {
    const icon = document.getElementById('myicon');
    icon.addEventListener('click', toggleBox);

    const cartIcon = document.getElementById('cartIcon');
    const cartBox = document.getElementById('cartBox');
    cartIcon.addEventListener('click', toggleCartBox);

    // Thêm sự kiện cho biểu tượng chuông
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationPopup = document.getElementById('notificationPopup');

    notificationIcon.addEventListener('click', () => {
        // Hiển thị hoặc ẩn popup
        notificationPopup.style.display = notificationPopup.style.display === 'none' || notificationPopup.style.display === '' ? 'block' : 'none';
    });

    // Ẩn popup khi nhấp ra ngoài
    window.addEventListener('click', (event) => {
        if (!notificationIcon.contains(event.target) && !notificationPopup.contains(event.target)) {
            notificationPopup.style.display = 'none'; // Ẩn popup
        }
    });

    const searchIcon = document.querySelector("#myIcon");
    const searchOverlay = document.querySelector("#search-overlay");
    const closeSearchButton = document.querySelector("#close-search");
    const searchInput = document.querySelector("#search-input");
    const searchResults = document.querySelector("#search-results");

    searchIcon.addEventListener("click", function() {
        searchOverlay.style.display = "flex"; // Hiển thị hộp tìm kiếm
    });
    
    closeSearchButton.addEventListener("click", function() {
        searchOverlay.style.display = "none"; // Ẩn hộp tìm kiếm
        searchResults.innerHTML = ""; // Xóa kết quả khi đóng
    });

    searchInput.addEventListener("input", function() {
        const query = searchInput.value;

        if (query.length > 0) {
            fetch(`./view/assets/php/search.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // In dữ liệu để kiểm tra
                    searchResults.innerHTML = ""; // Xóa kết quả trước khi hiển thị mới
                    if (data.length > 0) {
                        data.forEach(item => {
                            const a = document.createElement("a"); // Tạo thẻ <a> để liên kết
                            a.href = `index.php?page=blog-detail&id=${item.id}`; // Đường dẫn đến bài viết
                            a.textContent = item.course_name; // Hiển thị tên bài viết

                            a.style.display = "block"; // Đảm bảo mỗi thẻ <a> trên một dòng
                            searchResults.appendChild(a);
                        });
                    } else if (data.error) {
                        searchResults.innerHTML = `<p>${data.error}</p>`; // Hiển thị thông báo lỗi
                    } else {
                        searchResults.innerHTML = "<p>Không tìm thấy kết quả</p>";
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            searchResults.innerHTML = ""; // Xóa kết quả nếu không có gì được nhập
        }
    });



    document.getElementById('getDiscountButton').addEventListener('click', function() {
        // Cập nhật nội dung của promotionText
        document.getElementById('promotionText').innerText = '" Get promotional code: GAUMAUHONG "';
    });
    
    document.getElementById('cancelButton').addEventListener('click', function() {
        // Đặt lại nội dung khi nhấn nút Cancel
        document.getElementById('promotionText').innerText = '" Get promotional code. "';
    });
});

function toggleBox() {
    const notLoginBox = document.getElementById('not-login');
    const alreadyLoginBox = document.getElementById('already-login');

    console.log('Not Login Box:', notLoginBox);
    console.log('Already Login Box:', alreadyLoginBox);

    // Kiểm tra và hiển thị hộp tương ứng
    if (notLoginBox) {
        // Toggle hiển thị hộp chưa đăng nhập
        if (notLoginBox.style.display === 'none' || notLoginBox.style.display === '') {
            notLoginBox.style.display = 'block'; // Hiện hộp chưa đăng nhập
            alreadyLoginBox.style.display = 'none'; // Ẩn hộp đã đăng nhập
        } else {
            notLoginBox.style.display = 'none'; // Ẩn hộp chưa đăng nhập
        }
    }

    if (alreadyLoginBox) {
        // Toggle hiển thị hộp đã đăng nhập
        if (alreadyLoginBox.style.display === 'none' || alreadyLoginBox.style.display === '') {
            alreadyLoginBox.style.display = 'block'; // Hiện hộp đã đăng nhập
            notLoginBox.style.display = 'none'; // Ẩn hộp chưa đăng nhập
        } else {
            alreadyLoginBox.style.display = 'none'; // Ẩn hộp đã đăng nhập
        }
    }
}

// popup change nick name
document.getElementById("addnickname").onclick = function() {
    document.getElementById("addnicknamemodal").style.display = "block";
}
document.getElementById("closeModal").onclick = function() {
    document.getElementById("addnicknamemodal").style.display = "none";
};

// Hàm để mở/đóng giỏ hàng
function toggleCartBox() {
    const cartBox = document.getElementById('cartBox');
    
    if (cartBox) {
        cartBox.style.display = cartBox.style.display === 'none' ? 'block' : 'none';
    }
}

