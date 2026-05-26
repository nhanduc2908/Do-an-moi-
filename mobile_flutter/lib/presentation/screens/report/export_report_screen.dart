import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/report_provider.dart';
import '../../widgets/common/custom_button.dart';

class ExportReportScreen extends ConsumerStatefulWidget {
  const ExportReportScreen({super.key, required this.reportId});
  final String reportId;

  @override
  ConsumerState<ExportReportScreen> createState() => _ExportReportScreenState();
}

class _ExportReportScreenState extends ConsumerState<ExportReportScreen> {
  String _format = 'PDF';
  bool _isExporting = false;

  Future<void> _exportReport() async {
    setState(() => _isExporting = true);
    
    await ref.read(reportProvider.notifier).exportReport(widget.reportId, _format.toLowerCase());
    
    setState(() => _isExporting = false);
    
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Report exported as $_format')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Export Report'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Text('Select Export Format', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        _buildFormatOption('PDF', Icons.picture_as_pdf),
                        _buildFormatOption('Excel', Icons.table_chart),
                        _buildFormatOption('CSV', Icons.code),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Export Report',
              onPressed: _exportReport,
              isLoading: _isExporting,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFormatOption(String format, IconData icon) {
    final isSelected = _format == format;
    return InkWell(
      onTap: () => setState(() => _format = format),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isSelected ? Colors.blue.withOpacity(0.1) : null,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: isSelected ? Colors.blue : Colors.grey.shade300),
        ),
        child: Column(
          children: [
            Icon(icon, size: 32, color: isSelected ? Colors.blue : Colors.grey),
            const SizedBox(height: 8),
            Text(format, style: TextStyle(color: isSelected ? Colors.blue : Colors.grey)),
          ],
        ),
      ),
    );
  }
}