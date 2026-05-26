import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

class AuditTrailScreen extends ConsumerStatefulWidget {
  const AuditTrailScreen({super.key});

  @override
  ConsumerState<AuditTrailScreen> createState() => _AuditTrailScreenState();
}

class _AuditTrailScreenState extends ConsumerState<AuditTrailScreen> {
  String _selectedSeverity = 'All';
  String _selectedEventType = 'All';
  String _searchQuery = '';
  
  final List<Map<String, dynamic>> _logs = [
    {'time': '2024-01-15 10:30:00', 'user': 'admin@demo.com', 'action': 'User Created', 'ip': '192.168.1.1', 'severity': 'info', 'event_type': 'user'},
    {'time': '2024-01-15 09:45:00', 'user': 'system', 'action': 'Backup Completed', 'ip': '-', 'severity': 'info', 'event_type': 'system'},
    {'time': '2024-01-15 08:20:00', 'user': 'security@demo.com', 'action': 'Login Failed', 'ip': '203.0.113.45', 'severity': 'warning', 'event_type': 'auth'},
    {'time': '2024-01-14 23:15:00', 'user': 'admin@demo.com', 'action': 'Role Updated', 'ip': '192.168.1.1', 'severity': 'info', 'event_type': 'role'},
    {'time': '2024-01-14 18:30:00', 'user': 'attacker@example.com', 'action': 'Multiple Failed Logins', 'ip': '45.67.89.10', 'severity': 'critical', 'event_type': 'security'},
    {'time': '2024-01-14 14:00:00', 'user': 'system', 'action': 'Database Optimized', 'ip': '-', 'severity': 'info', 'event_type': 'system'},
    {'time': '2024-01-14 09:30:00', 'user': 'compliance@demo.com', 'action': 'Report Generated', 'ip': '192.168.1.50', 'severity': 'info', 'event_type': 'report'},
    {'time': '2024-01-13 16:45:00', 'user': 'admin@demo.com', 'action': 'Permission Changed', 'ip': '192.168.1.1', 'severity': 'warning', 'event_type': 'permission'},
    {'time': '2024-01-13 11:20:00', 'user': 'system', 'action': 'Security Scan Completed', 'ip': '-', 'severity': 'info', 'event_type': 'security'},
    {'time': '2024-01-13 07:00:00', 'user': 'system', 'action': 'Daily Backup', 'ip': '-', 'severity': 'info', 'event_type': 'backup'},
  ];

  List<Map<String, dynamic>> get _filteredLogs {
    return _logs.where((log) {
      if (_selectedSeverity != 'All' && log['severity'] != _selectedSeverity) return false;
      if (_selectedEventType != 'All' && log['event_type'] != _selectedEventType) return false;
      if (_searchQuery.isNotEmpty) {
        final query = _searchQuery.toLowerCase();
        return log['user'].toLowerCase().contains(query) ||
               log['action'].toLowerCase().contains(query) ||
               log['ip'].toLowerCase().contains(query);
      }
      return true;
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Audit Trail'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_alt),
            onPressed: _showFilterDialog,
          ),
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: _exportLogs,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: TextField(
              decoration: InputDecoration(
                hintText: 'Search logs...',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              ),
              onChanged: (value) => setState(() => _searchQuery = value),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              children: [
                const Text('Filter: ', style: TextStyle(fontWeight: FontWeight.bold)),
                const SizedBox(width: 8),
                Expanded(
                  child: SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        FilterChip(
                          label: const Text('All'),
                          selected: _selectedSeverity == 'All',
                          onSelected: (_) => setState(() => _selectedSeverity = 'All'),
                        ),
                        const SizedBox(width: 8),
                        FilterChip(
                          label: const Text('Critical'),
                          selected: _selectedSeverity == 'critical',
                          onSelected: (_) => setState(() => _selectedSeverity = 'critical'),
                          backgroundColor: Colors.red.shade50,
                          selectedColor: Colors.red.shade100,
                        ),
                        const SizedBox(width: 8),
                        FilterChip(
                          label: const Text('Warning'),
                          selected: _selectedSeverity == 'warning',
                          onSelected: (_) => setState(() => _selectedSeverity = 'warning'),
                          backgroundColor: Colors.orange.shade50,
                          selectedColor: Colors.orange.shade100,
                        ),
                        const SizedBox(width: 8),
                        FilterChip(
                          label: const Text('Info'),
                          selected: _selectedSeverity == 'info',
                          onSelected: (_) => setState(() => _selectedSeverity = 'info'),
                          backgroundColor: Colors.blue.shade50,
                          selectedColor: Colors.blue.shade100,
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _filteredLogs.length,
              itemBuilder: (context, index) {
                final log = _filteredLogs[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getSeverityColor(log['severity']),
                      child: Icon(_getSeverityIcon(log['severity']), color: Colors.white, size: 20),
                    ),
                    title: Text(
                      log['action'],
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('User: ${log['user']} • IP: ${log['ip']}'),
                        Text(log['time'], style: const TextStyle(fontSize: 12, color: Colors.grey)),
                      ],
                    ),
                    trailing: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: _getSeverityColor(log['severity']).withOpacity(0.2),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        log['severity'].toUpperCase(),
                        style: TextStyle(
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                          color: _getSeverityColor(log['severity']),
                        ),
                      ),
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
              value: _selectedEventType,
              decoration: const InputDecoration(labelText: 'Event Type'),
              items: [
                'All', 'user', 'auth', 'security', 'system', 'role', 'permission', 'report', 'backup'
              ].map((type) => DropdownMenuItem(value: type, child: Text(type.toUpperCase()))).toList(),
              onChanged: (value) => setState(() => _selectedEventType = value ?? 'All'),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedSeverity,
              decoration: const InputDecoration(labelText: 'Severity'),
              items: ['All', 'critical', 'warning', 'info']
                  .map((sev) => DropdownMenuItem(value: sev, child: Text(sev.toUpperCase()))).toList(),
              onChanged: (value) => setState(() => _selectedSeverity = value ?? 'All'),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              setState(() {});
            },
            child: const Text('Apply'),
          ),
        ],
      ),
    );
  }

  void _showLogDetails(Map<String, dynamic> log) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => Container(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(_getSeverityIcon(log['severity']), color: _getSeverityColor(log['severity'])),
                const SizedBox(width: 8),
                Text(
                  log['action'],
                  style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
              ],
            ),
            const Divider(height: 24),
              _buildDetailRow('User', log['user']),
              _buildDetailRow('IP Address', log['ip']),
              _buildDetailRow('Time', log['time']),
              _buildDetailRow('Severity', log['severity'].toUpperCase()),
              _buildDetailRow('Event Type', log['event_type'].toUpperCase()),
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey),
            ),
          ),
          Expanded(
            child: Text(value),
          ),
        ],
      ),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity) {
      case 'critical': return Colors.red;
      case 'warning': return Colors.orange;
      default: return Colors.blue;
    }
  }

  IconData _getSeverityIcon(String severity) {
    switch (severity) {
      case 'critical': return Icons.error;
      case 'warning': return Icons.warning;
      default: return Icons.info;
    }
  }

  void _exportLogs() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Export Audit Logs'),
        content: const Text('Are you sure you want to export the filtered audit logs?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Export started. You will be notified when ready.')),
              );
            },
            child: const Text('Export'),
          ),
        ],
      ),
    );
  }
}