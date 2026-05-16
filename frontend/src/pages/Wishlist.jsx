import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api/axios';
import ProductCard from '../components/ProductCard';

export default function Wishlist() {
  const [items, setItems] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    api.get('/wishlist').then((r) => setItems(r.data)).catch(() => navigate('/login'));
  }, []);

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">My Wishlist</h1>
      {items.length === 0 ? (
        <p className="text-gray-500 text-center py-10">Your wishlist is empty.</p>
      ) : (
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {items.map((item) => <ProductCard key={item.id} product={item.product} />)}
        </div>
      )}
    </div>
  );
}
