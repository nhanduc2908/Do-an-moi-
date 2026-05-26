import '../../core/utils/logger.dart';

class ConflictResolver {
  static const String strategyServer = 'server_wins';
  static const String strategyClient = 'client_wins';
  static const String strategyMerge = 'merge';

  Future<Map<String, dynamic>> resolve(
    Map<String, dynamic> localData,
    Map<String, dynamic> serverData,
    String strategy,
  ) async {
    switch (strategy) {
      case strategyServer:
        return serverData;
      case strategyClient:
        return localData;
      case strategyMerge:
        return _merge(localData, serverData);
      default:
        return serverData;
    }
  }

  Map<String, dynamic> _merge(Map<String, dynamic> local, Map<String, dynamic> server) {
    final merged = Map<String, dynamic>.from(server);
    for (final key in local.keys) {
      if (local[key] != server[key]) {
        merged[key] = local[key];
        Logger.sync('Conflict resolved for key: $key');
      }
    }
    return merged;
  }
}