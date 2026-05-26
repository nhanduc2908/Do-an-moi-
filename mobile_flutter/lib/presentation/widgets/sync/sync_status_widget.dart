import 'package:flutter/material.dart';

class SyncStatusWidget extends StatelessWidget {
  final bool isSyncing;
  final DateTime? lastSyncTime;
  final int pendingItems;

  const SyncStatusWidget({
    super.key,
    required this.isSyncing,
    this.lastSyncTime,
    required this.pendingItems,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: isSyncing ? Colors.blue.shade50 : Colors.green.shade50,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        children: [
          if (isSyncing)
            const SizedBox(
              width: 20,
              height: 20,
              child: CircularProgressIndicator(strokeWidth: 2),
            )
          else
            const Icon(Icons.check_circle, color: Colors.green, size: 20),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  isSyncing ? 'Syncing...' : 'Sync completed',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                if (lastSyncTime != null)
                  Text(
                    'Last sync: ${_formatTime(lastSyncTime!)}',
                    style: const TextStyle(fontSize: 12, color: Colors.grey),
                  ),
                if (pendingItems > 0)
                  Text(
                    '$pendingItems items pending',
                    style: const TextStyle(fontSize: 12, color: Colors.orange),
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String _formatTime(DateTime time) {
    final now = DateTime.now();
    final diff = now.difference(time);
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    return '${diff.inDays}d ago';
  }
}