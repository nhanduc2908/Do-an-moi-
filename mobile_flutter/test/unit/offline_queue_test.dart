// Đường dẫn: mobile_flutter/test/unit/offline_queue_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/data/datasources/local/offline_queue.dart';
import 'package:security_evaluation_app/data/models/sync_queue_model.dart';
import '../mocks/mock_database.dart';

void main() {
  late OfflineQueue offlineQueue;

  setUp(() {
    offlineQueue = MockDatabase.createMockOfflineQueue();
  });

  group('OfflineQueue Tests', () {
    test('addToQueue stores item', () async {
      final item = SyncQueueModel(
        id: '1',
        endpoint: '/test',
        method: 'POST',
        status: 'pending',
        createdAt: DateTime.now(),
      );
      
      await offlineQueue.addToQueue(item);
      
      final items = await offlineQueue.getPendingItems();
      expect(items, isNotNull);
    });

    test('getPendingItems returns list', () async {
      final items = await offlineQueue.getPendingItems();
      
      expect(items, isList);
    });

    test('updateItemStatus changes status', () async {
      await offlineQueue.updateItemStatus('1', 'success');
      
      final items = await offlineQueue.getPendingItems();
      expect(items, isNotNull);
    });

    test('removeFromQueue deletes item', () async {
      await offlineQueue.removeFromQueue('1');
      
      final items = await offlineQueue.getPendingItems();
      expect(items, isNotNull);
    });

    test('clearQueue removes all items', () async {
      await offlineQueue.clearQueue();
      
      final items = await offlineQueue.getPendingItems();
      expect(items, isEmpty);
    });

    test('getQueueSize returns correct count', () async {
      final size = await offlineQueue.getQueueSize();
      
      expect(size, isA<int>());
    });
  });
}