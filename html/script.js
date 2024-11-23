document.querySelector('.info-icon').addEventListener('mouseenter', () => {
    const tooltip = document.querySelector('.tooltip');
    tooltip.style.opacity = '1';
    tooltip.style.visibility = 'visible';
  });
  
  document.querySelector('.info-icon').addEventListener('mouseleave', () => {
    const tooltip = document.querySelector('.tooltip');
    tooltip.style.opacity = '0';
    tooltip.style.visibility = 'hidden';
  });
  