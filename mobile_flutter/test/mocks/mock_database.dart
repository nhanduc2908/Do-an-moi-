// Đường dẫn: mobile_flutter/test/mocks/mock_database.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/data/datasources/local/sync_database.dart';
import 'package:security_evaluation_app/data/models/sync_queue_model.dart';

@GenerateMocks([SyncDatabase])

class MockDatabase {
  static MockSyncDatabase createMockSyncDatabase() {
    final mock = MockSyncDatabase();
    
    when(mock.getPendingSyncItems()).thenAnswer((_) async => []);
    when(mock.insertSyncQueue(any)).thenAnswer((_) async {});
    when(mock.updateSyncStatus(any, any)).thenAnswer((_) async {});
    when(mock.deleteSyncQueueItem(any)).thenAnswer((_) async {});
    when(mock.clearOldSyncLogs(any)).thenAnswer((_) async {});
    
    return mock;
  }

  static MockSyncDatabase createMockSyncDatabaseWithItems() {
    final mock = MockSyncDatabase();
    final items = [
      SyncQueueModel(
        id: '1',
        endpoint: '/test',
        method: 'POST',
        status: 'pending',
        createdAt: DateTime.now(),
      ),
    ];
    
    when(mock.getPendingSyncItems()).thenAnswer((_) async => items);
    when(mock.insertSyncQueue(any)).thenAnswer((_) async {});
    when(mock.updateSyncStatus(any, any)).thenAnswer((_) async {});
    
    return mock;
  }
}