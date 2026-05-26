// Đường dẫn: mobile_flutter/lib/firebase/firestore_sync.dart

import 'package:cloud_firestore/cloud_firestore.dart';

class FirestoreSync {
  final FirebaseFirestore _firestore = FirebaseFirestore.instance;

  Future<void> syncData(String collectionPath, Map<String, dynamic> data) async {
    await _firestore.collection(collectionPath).add(data);
  }

  Future<void> updateData(String collectionPath, String docId, Map<String, dynamic> data) async {
    await _firestore.collection(collectionPath).doc(docId).update(data);
  }

  Stream<QuerySnapshot> streamCollection(String collectionPath) {
    return _firestore.collection(collectionPath).snapshots();
  }

  Future<DocumentSnapshot> getDocument(String collectionPath, String docId) async {
    return await _firestore.collection(collectionPath).doc(docId).get();
  }
}