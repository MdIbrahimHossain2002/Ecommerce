import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../api/axios';
import { formatPrice } from '../utils/format';

export default function Orders() {
  const [orders, setOrders] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    api.get('/orders').then((r) => setOrders(r.data.data || r.data)).catch(() => navigate('/login'));
  }, []);

  const statusColor = { pending: 'bg-yellow-100 text-yellow-800', processing: 'bg-blue-100 text-blue-800', shipped: 'bg-purple-100 text-purple-800', delivered: 'bg-green-100 text-green-800', cancelled: 'bg-red-100 text-red-800' };

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">My Orders</h1>
      <div className="space-y-4">
        {orders.map((order) => (
          <Link key={order.id} to={`/orders/${order.order_number}`} className="card p-4 flex justify-between items-center hover:shadow-md">
            <div>
              <p className="font-medium">{order.order_number}</p>
              <p className="text-sm text-gray-500">{new Date(order.created_at).toLocaleDateString()}</p>
            </div>
            <div className="text-right">
              <p className="font-bold text-brand-600">{formatPrice(order.total)}</p>
              <span className={`text-xs px-2 py-1 rounded-full ${statusColor[order.status] || 'bg-gray-100'}`}>{order.status}</span>
            </div>
          </Link>
        ))}
        {orders.length === 0 && <p className="text-center text-gray-500 py-10">No orders yet.</p>}
      </div>
    </div>
  );
}
