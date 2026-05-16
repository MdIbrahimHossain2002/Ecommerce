import { useEffect, useState } from 'react';
import api from '../api/axios';
import { formatPrice, getProductImage } from '../utils/format';

export default function AdminProducts() {
  const [products, setProducts] = useState([]);

  const load = () => api.get('/admin/products').then((r) => setProducts(r.data.data || []));

  useEffect(() => { load(); }, []);

  const remove = async (id) => {
    if (!confirm('Delete this product?')) return;
    await api.delete(`/admin/products/${id}`);
    load();
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold">Products</h1>
        <button className="btn-primary">+ Add Product</button>
      </div>
      <div className="card overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50">
            <tr><th className="p-3 text-left">Product</th><th className="p-3 text-left">SKU</th><th className="p-3 text-left">Price</th><th className="p-3 text-left">Stock</th><th className="p-3 text-left">Status</th><th className="p-3"></th></tr>
          </thead>
          <tbody>
            {products.map((p) => (
              <tr key={p.id} className="border-t">
                <td className="p-3 flex items-center gap-3">
                  <img src={getProductImage(p)} alt="" className="w-10 h-10 rounded object-cover" />
                  {p.name}
                </td>
                <td className="p-3">{p.sku}</td>
                <td className="p-3">{formatPrice(p.discount_price || p.regular_price)}</td>
                <td className="p-3">{p.stock}</td>
                <td className="p-3 capitalize">{p.status}</td>
                <td className="p-3"><button onClick={() => remove(p.id)} className="text-red-500 text-sm">Delete</button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
