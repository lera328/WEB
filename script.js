document.addEventListener('DOMContentLoaded', function() {
    // Обработчик клика по карточке
    document.querySelectorAll('.component-card').forEach(card => {
      card.addEventListener('click', function() {
        const modalId = this.getAttribute('data-modal-id');
        document.getElementById(modalId).style.display = 'block';
      });
    });
  
    // Закрытие модального окна
    document.querySelectorAll('.close, .modal').forEach(element => {
      element.addEventListener('click', function(e) {
        if (e.target === this || e.target.classList.contains('close')) {
          this.closest('.modal').style.display = 'none';
        }
      });
    });
  });