import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function FAQ() {
  const [faqs, setFaqs] = useState([]);

  useEffect(() => {
    api.get('/faqs').then((r) => setFaqs(r.data)).catch(console.error);
  }, []);

  return (
    <div className="max-w-3xl mx-auto px-4 py-10">
      <h1 className="text-3xl font-bold mb-8">Frequently Asked Questions</h1>
      <div className="space-y-4">
        {faqs.map((faq) => (
          <details key={faq.id} className="card p-4">
            <summary className="font-medium cursor-pointer">{faq.question}</summary>
            <p className="mt-3 text-gray-600 text-sm">{faq.answer}</p>
          </details>
        ))}
      </div>
    </div>
  );
}
