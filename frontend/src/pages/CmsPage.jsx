import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import api from '../api/axios';

export default function CmsPage() {
  const { slug } = useParams();
  const [page, setPage] = useState(null);

  useEffect(() => {
    api.get(`/pages/${slug}`).then((r) => setPage(r.data)).catch(console.error);
  }, [slug]);

  if (!page) return <div className="text-center py-20">Loading...</div>;

  return (
    <div className="max-w-3xl mx-auto px-4 py-10">
      <h1 className="text-3xl font-bold mb-6">{page.title}</h1>
      <div className="prose max-w-none text-gray-600" dangerouslySetInnerHTML={{ __html: page.content }} />
    </div>
  );
}
