import 'package:flutter/material.dart';

class ComplianceReportsScreen extends StatefulWidget {
  const ComplianceReportsScreen({super.key});

  @override
  State<ComplianceReportsScreen> createState() => _ComplianceReportsScreenState();
}

class _ComplianceReportsScreenState extends State<ComplianceReportsScreen> {
  final List<Map<String, dynamic>> _reports = [
    {'name': 'ISO 27001 Compliance Report', 'type': 'ISO 27001', 'date': '2024-01-15', 'status': 'Generated', 'size': '2.4 MB'},
    {'name': 'GDPR Readiness Assessment', 'type': 'GDPR', 'date': '2024-01-10', 'status': 'Generated', 'size': '1.8 MB'},
    {'name': 'PCI DSS Gap Analysis', 'type': 'PCI DSS', 'date': '2024-01-05', 'status': 'Draft', 'size': '3.1 MB'},
    {'name': 'Q4 Compliance Summary', 'type': 'Summary', 'date': '2023-12-28', 'status': 'Generated', 'size': '5.2 MB'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Compliance Reports'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _showGenerateReportDialog(),
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _reports.length,
        itemBuilder: (context, index) {
          final report = _reports[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: const Icon(Icons.description, size: 40, color: Colors.blue),
              title: Text(report['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${report['type']} • ${report['date']} • ${report['size']}'),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  if (report['status'] == 'Generated')
                    IconButton(
                      icon: const Icon(Icons.download, color: Colors.blue),
                      onPressed: () {},
                    ),
                  IconButton(
                    icon: const Icon(Icons.share),
                    onPressed: () {},
                  ),
                ],
              ),
              onTap: () {},
            ),
          );
        },
      ),
    );
  }

  void _showGenerateReportDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Generate Compliance Report'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'ISO 27001', child: Text('ISO 27001 Compliance Report')),
                DropdownMenuItem(value: 'GDPR', child: Text('GDPR Readiness Report')),
                DropdownMenuItem(value: 'PCI DSS', child: Text('PCI DSS Compliance Report')),
                DropdownMenuItem(value: 'Summary', child: Text('Compliance Summary')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Report Type'),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'PDF', child: Text('PDF')),
                DropdownMenuItem(value: 'Excel', child: Text('Excel')),
                DropdownMenuItem(value: 'CSV', child: Text('CSV')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Format'),
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Generate')),
        ],
      ),
    );
  }
}