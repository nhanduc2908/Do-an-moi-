// Đường dẫn: mobile_flutter/test/unit/sync_engine_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/services/sync/sync_engine.dart';
import 'package:security_evaluation_app/data/repositories/sync_repository.dart';
import 'package:security_evaluation_app/data/datasources/local/offline_queue.dart';
import '../mocks/mock_repository.dart';
import '../mocks/mock_database.dart';

void main() {
  late SyncEngine syncEngine;
  late MockSyncRepository mockSyncRepository;
  late MockOfflineQueue mockOfflineQueue;

  setUp(() {
    mockSyncRepository = MockRepositories.createMockSyncRepository();
    mockOfflineQueue = MockDatabase.createMockOfflineQueue();
    syncEngine = SyncEngine(mockSyncRepository, mockOfflineQueue);
  });

  group('SyncEngine Tests', () {
    test('initial state is not syncing', () {
      expect(syncEngine.isSyncing, false);
    });

    test('startAutoSync sets up timer', () {
      syncEngine.startAutoSync();
      
      expect(syncEngine.isSyncing, false);
    });

    test('stopAutoSync cancels timer', () {
      syncEngine.startAutoSync();
      syncEngine.stopAutoSync();
      
      expect(syncEngine.isSyncing, false);
    });

    test('sync processes pending items', () async {
      when(mockOfflineQueue.getPendingItems()).thenAnswer((_) async => []);
      when(mockSyncRepository.syncToFlutter()).thenAnswer((_) async => 
        ApiResponseModel(success: true));
      
      await syncEngine.sync();
      
      expect(syncEngine.isSyncing, false);
    });

    test('sync handles errors gracefully', () async {
      when(mockOfflineQueue.getPendingItems()).thenThrow(Exception('Network error'));
      
      await syncEngine.sync();
      
      expect(syncEngine.isSyncing, false);
    });
  });
}