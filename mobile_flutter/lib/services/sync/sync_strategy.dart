enum SyncStrategyType { full, incremental, batch, realtime }

class SyncStrategy {
  final SyncStrategyType type;
  final Duration interval;

  SyncStrategy(this.type, this.interval);

  static SyncStrategy full = SyncStrategy(SyncStrategyType.full, Duration.zero);
  static SyncStrategy incremental = SyncStrategy(SyncStrategyType.incremental, const Duration(minutes: 15));
  static SyncStrategy batch = SyncStrategy(SyncStrategyType.batch, const Duration(minutes: 5));
  static SyncStrategy realtime = SyncStrategy(SyncStrategyType.realtime, const Duration(seconds: 10));

  static SyncStrategy fromString(String type) {
    switch (type) {
      case 'full': return full;
      case 'incremental': return incremental;
      case 'batch': return batch;
      case 'realtime': return realtime;
      default: return incremental;
    }
  }
}