import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../api/axios';
import ProductCard from '../components/ProductCard';

function ProductSection({ title, products, link }) {
  if (!products?.length) return null;
  return (
    <section className="max-w-7xl mx-auto px-4 py-10">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-bold">{title}</h2>
        {link && <Link to={link} className="text-brand-600 font-medium hover:underline">View All →</Link>}
      </div>
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        {products.map((p) => <ProductCard key={p.id} product={p} />)}
      </div>
    </section>
  );
}

export default function Home() {
  const [data, setData] = useState(null);

  useEffect(() => {
    api.get('/home').then((res) => setData(res.data)).catch(console.error);
  }, []);

  if (!data) return <div className="flex justify-center py-20"><div className="animate-pulse text-gray-400">Loading...</div></div>;

  return (
    <div>
      {/* Hero Slider */}
      <div className="relative overflow-hidden bg-gray-900">
        {data.banners?.map((banner, i) => (
          <div key={banner.id} className={`${i === 0 ? 'block' : 'hidden'} relative`}>
            <img src={banner.image} alt={banner.title} className="w-full h-64 md:h-[500px] object-cover opacity-70" />
            <div className="absolute inset-0 flex items-center">
              <div className="max-w-7xl mx-auto px-4 text-white">
                <h1 className="text-3xl md:text-5xl font-bold mb-2">{banner.title}</h1>
                <p className="text-lg md:text-xl text-gray-200 mb-6">{banner.subtitle}</p>
                <Link to={banner.link || '/products'} className="btn-primary inline-block">
                  {banner.button_text || 'Shop Now'}
                </Link>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Categories */}
      <section className="max-w-7xl mx-auto px-4 py-10">
        <h2 className="text-2xl font-bold mb-6">Shop by Category</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {data.categories?.map((cat) => (
            <Link key={cat.id} to={`/products?category=${cat.slug}`} className="card p-6 text-center hover:shadow-md transition-shadow">
              <div className="w-16 h-16 bg-brand-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">📦</div>
              <h3 className="font-semibold">{cat.name}</h3>
            </Link>
          ))}
        </div>
      </section>

      <ProductSection title="Featured Products" products={data.featured} link="/products?featured=1" />
      <ProductSection title="Best Sellers" products={data.bestsellers} link="/products?bestseller=1" />
      <ProductSection title="New Arrivals" products={data.new_arrivals} link="/products?new_arrivals=1" />

      {/* Offers */}
      {data.on_sale?.length > 0 && (
        <section className="bg-red-50 py-10">
          <ProductSection title="🔥 Special Offers" products={data.on_sale} link="/products?on_sale=1" />
        </section>
      )}
    </div>
  );
}

