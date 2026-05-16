import { useEffect, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import api from '../api/axios';
import ProductCard from '../components/ProductCard';

export default function Products() {
  const [params, setParams] = useSearchParams();
  const [data, setData] = useState({ data: [], last_page: 1 });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    const query = Object.fromEntries(params);
    api.get('/products', { params: query })
      .then((res) => setData(res.data))
      .finally(() => setLoading(false));
  }, [params]);

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">All Products</h1>
      <div className="flex flex-col md:flex-row gap-6">
        <aside className="w-full md:w-56 shrink-0">
          <div className="card p-4 space-y-4">
            <div>
              <label className="text-sm font-medium block mb-1">Search</label>
              <input
                className="input text-sm"
                defaultValue={params.get('search') || ''}
                onKeyDown={(e) => e.key === 'Enter' && setParams({ ...Object.fromEntries(params), search: e.target.value, page: 1 })}
                placeholder="Search..."
              />
            </div>
            <div>
              <label className="text-sm font-medium block mb-1">Sort</label>
              <select
                className="input text-sm"
                value={params.get('sort') || 'latest'}
                onChange={(e) => setParams({ ...Object.fromEntries(params), sort: e.target.value })}
              >
                <option value="latest">Latest</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="popular">Popular</option>
              </select>
            </div>
          </div>
        </aside>
        <div className="flex-1">
          {loading ? (
            <p className="text-center py-10 text-gray-400">Loading products...</p>
          ) : (
            <>
              <div className="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                {data.data?.map((p) => <ProductCard key={p.id} product={p} />)}
              </div>
              {data.data?.length === 0 && <p className="text-center py-10 text-gray-500">No products found.</p>}
              {data.last_page > 1 && (
                <div className="flex justify-center gap-2 mt-8">
                  {Array.from({ length: data.last_page }, (_, i) => i + 1).map((page) => (
                    <button
                      key={page}
                      onClick={() => setParams({ ...Object.fromEntries(params), page })}
                      className={`px-3 py-1 rounded ${data.current_page === page ? 'bg-brand-600 text-white' : 'bg-gray-200'}`}
                    >
                      {page}
                    </button>
                  ))}
                </div>
              )}
            </>
          )}
        </div>
      </div>
    </div>
  );
}

