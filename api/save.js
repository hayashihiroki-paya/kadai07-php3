import admin from "firebase-admin";

// 初期化（二重防止）
if (!admin.apps.length) {
  admin.initializeApp({
    credential: admin.credential.cert(
      JSON.parse(process.env.FIREBASE_SERVICE_ACCOUNT_JSON) // 環境変数でキー秘匿
    )
  });
}

const db = admin.firestore();

export default async function handler(req, res) {
  // ===== CORS ヘッダー =====
  res.setHeader("Access-Control-Allow-Origin", "*");
  res.setHeader("Access-Control-Allow-Methods", "POST, OPTIONS");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type, Authorization");

  // ===== preflight 対応 =====
  if (req.method === "OPTIONS") {
    return res.status(200).end();
  }

  // ===== POST 以外は拒否 =====
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Method Not Allowed" });
  }


  try {
    const data = req.body;

    // 仮ログ（確認用）
    console.log("received:", data);

    if (!data?.isbn) {
      return res.status(400).json({ error: "isbn is required" });
    }

    await db.collection("books").doc(data.isbn).set({
      ...data,
      createdAt: admin.firestore.FieldValue.serverTimestamp()
    },
      { merge: true } // 上書き保存（更新）を許可する
    );

    res.status(200).json({ success: true });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Firestore save failed" });
  }
}
