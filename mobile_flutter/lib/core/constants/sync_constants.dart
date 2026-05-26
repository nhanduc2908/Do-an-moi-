class SyncConstants {
  static const String syncTypeFull = 'full';
  static const String syncTypeIncremental = 'incremental';
  static const String syncTypeBatch = 'batch';
  
  static const int priorityHigh = 1;
  static const int priorityMedium = 2;
  static const int priorityLow = 3;
  
  static const String syncStatusPending = 'pending';
  static const String syncStatusSyncing = 'syncing';
  static const String syncStatusSuccess = 'success';
  static const String syncStatusFailed = 'failed';
  
  static const Duration syncIntervalHigh = Duration(minutes: 5);
  static const Duration syncIntervalMedium = Duration(minutes: 15);
  static const Duration syncIntervalLow = Duration(hours: 1);
  
  static const int batchSizeSmall = 10;
  static const int batchSizeMedium = 50;
  static const int batchSizeLarge = 100;
  
  static const int maxRetries = 3;
  static const Duration retryDelay = Duration(seconds: 5);
  static const int maxQueueSize = 1000;
  static const int maxQueueRetentionDays = 7;
  
  static const String conflictStrategyServer = 'server_wins';
  static const String conflictStrategyClient = 'client_wins';
  static const String conflictStrategyMerge = 'merge';
}