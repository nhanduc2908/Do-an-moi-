// Đường dẫn: mobile_flutter/lib/firebase/realtime_database.dart

import 'package:firebase_database/firebase_database.dart';

class RealtimeDatabase {
  final DatabaseReference _database = FirebaseDatabase.instance.ref();

  Future<void> setData(String path, dynamic value) async {
    await _database.child(path).set(value);
  }

  Future<void> updateData(String path, Map<String, dynamic> value) async {
    await _database.child(path).update(value);
  }

  Stream<DatabaseEvent> streamData(String path) {
    return _database.child(path).onValue;
  }
}