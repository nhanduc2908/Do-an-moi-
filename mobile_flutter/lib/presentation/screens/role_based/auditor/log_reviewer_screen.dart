import 'package:flutter/material.dart';

class LogReviewerScreen extends StatefulWidget {
  const LogReviewerScreen({super.key});

  @override
  State<LogReviewerScreen> createState() => _LogReviewerScreenState();
}

class _LogReviewerScreenState extends State<LogReviewerScreen> {
  String _selectedLogType = 'All';
  String _selectedSeverity = 'All';
  DateTimeRange? _dateRange;
  
  final List<Map<String, dynamic>> _logs = [
    {'time': '2024-01-15 10:30:00', 'level': 'ERROR', 'source': 'AuthService', 'message': 'Login failed for user admin', 'user': 'admin@demo.com', 'ip': '192.168.1.1'},
    {'time': '2024-01-15 09:45:00', 'level': 'WARNING', 'source': 'Security', 'message': 'Multiple failed login attempts', 'user': 'unknown', 'ip': '203.0.113.45'},
    {'time': '2024-01-15 08:20:00', 'level': 'INFO', 'source': 'System', 'message': 'Backup completed successfully', 'user': 'system', 'ip': '-'},
    {'time': '2024-01-14 23:15:00', 'level': 'ERROR', 'source': 'Database', 'message': 'Connection timeout', 'user': 'app', 'ip': '127.0.0.1'},
    {'time': '2024-01-14 18:30:00', 'level': 'INFO', 'source': 'Audit', 'message': 'User role changed', 'user': 'admin@demo.com', 'ip': '192.168.1.1'},
  ];

  List<Map<String, dynamic>> get _filteredLogs {
    return _logs.where((log) {
      if (_selectedLogType != 'All' && log['source'] != _selectedLogType) return false;
      if (_selectedSeverity != 'All' && log['level'] != _selectedSeverity) return false;
      if (_dateRange != null) {
        final logDate = DateTime.parse(log['time'].split(' ')[0]);
        if (logDate.isBefore(_dateRange!.start) || logDate.isAfter(_dateRange!.end)) return false;
      }
      return true;
    }).toList();
  }

  Future<void> _selectDateRange() async {
    final picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime(2020),
      lastDate: DateTime.now(),
    );
    if (picked != null) setState(() => _dateRange = picked);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Log Reviewer'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _showFilterDialog(),
          ),
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          if (_dateRange != null)
            Container(
              padding: const EdgeInsets.all(8),
              color: Colors.blue.shade50,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('${_dateRange!.start.toLocal().toString().split(' ')[0]} - ${_dateRange!.end.toLocal().toString().split(' ')[0]}'),
                  IconButton(
                    icon: const Icon(Icons.close, size: 16),
                    onPressed: () => setState(() => _dateRange = null),
                  ),
                ],
              ),
            ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _filteredLogs.length,
              itemBuilder: (context, index) {
                final log = _filteredLogs[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getLevelColor(log['level']),
                      child: Text(log['level'].substring(0, 1), style: const TextStyle(color: Colors.white, fontSize: 12)),
                    ),
                    title: Text(log['message'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Source: ${log['source']} • User: ${log['user']}'),
                        Text('Time: ${log['time']} • IP: ${log['ip']}', style: const TextStyle(fontSize: 12)),
                      ],
                    ),
                    onTap: () => _showLogDetails(log),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  void _showFilterDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Filter Logs'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              value: _selectedLogType,
              items: const [
                DropdownMenuItem(value: 'All', child: Text('All Sources')),
                DropdownMenuItem(value: 'AuthService', child: Text('AuthService')),
                DropdownMenuItem(value: 'Security', child: Text('Security')),
                DropdownMenuItem(value: 'System', child: Text('System')),
                DropdownMenuItem(value: 'Database', child: Text('Database')),
              ],
              onChanged: (value) => setState(() => _selectedLogType = value!),
              decoration: const InputDecoration(labelText: 'Log Source'),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _selectedSeverity,
              items: const [
                DropdownMenuItem(value: 'All', child: Text('All Levels')),
                DropdownMenuItem(value: 'ERROR', child: Text('ERROR')),
                DropdownMenuItem(value: 'WARNING', child: Text('WARNING')),
                DropdownMenuItem(value: 'INFO', child: Text('INFO')),
              ],
              onChanged: (value) => setState(() => _selectedSeverity = value!),
              decoration: const InputDecoration(labelText: 'Severity'),
            ),
            const SizedBox(height: 12),
            ListTile(
              title: const Text('Date Range'),
              subtitle: Text(_dateRange != null 
                  ? '${_dateRange!.start.toLocal().toString().split(' ')[0]} - ${_dateRange!.end.toLocal().toString().split(' ')[0]}'
                  : 'Select date range'),
              trailing: const Icon(Icons.calendar_today),
              onTap: _selectDateRange,
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () {
              setState(() {
                _selectedLogType = 'All';
                _selectedSeverity = 'All';
                _dateRange = null;
              });
              Navigator.pop(context);
            },
            child: const Text('Reset'),
          ),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Apply')),
        ],
      ),
    );
  }

  void _showLogDetails(Map<String, dynamic> log) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: _getLevelColor(log['level']).withOpacity(0.2),
                    borderRadius: BorderRadius.circular(4),
                  ),
                  child: Text(log['level'], style: TextStyle(color: _getLevelColor(log['level']), fontWeight: FontWeight.bold)),
                ),
                const Spacer(),
                Text(log['time'], style: const TextStyle(color: Colors.grey)),
              ],
            ),
            const Divider(height: 24),
            _buildDetailRow('Message', log['message']),
            _buildDetailRow('Source', log['source']),
            _buildDetailRow('User', log['user']),
            _buildDetailRow('IP Address', log['ip']),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Close'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        children: [
          SizedBox(width: 80, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }

  Color _getLevelColor(String level) {
    switch (level) {
      case 'ERROR': return Colors.red;
      case 'WARNING': return Colors.orange;
      default: return Colors.blue;
    }
  }
}