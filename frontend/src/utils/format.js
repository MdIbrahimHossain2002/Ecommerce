export const formatPrice = (price, symbol = '৳') =>
  `${symbol}${Number(price).toLocaleString('en-BD', { minimumFractionDigits: 0 })}`;

export const getProductImage = (product) =>
  product?.images?.[0]?.path || product?.primary_image || 'https://via.placeholder.com/400';

export const getEffectivePrice = (product) =>
  Number(product.discount_price ?? product.regular_price);
